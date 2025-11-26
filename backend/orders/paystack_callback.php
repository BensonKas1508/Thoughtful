<?php
// backend/orders/paystack_callback.php
// Paystack webhook handler
// - Verifies signature
// - Updates order record
// - Logs webhook payload
// - Notifies user & vendor(s)

require_once __DIR__ . "/../config/db.php";      // must provide $pdo and $paystack_secret_key
require_once __DIR__ . "/../helpers/response.php"; // jsonResponse helper

// Read raw payload
$payload = @file_get_contents('php://input');
if ($payload === false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No payload']);
    exit;
}

// Get Paystack signature header
$headers = function_exists('getallheaders') ? getallheaders() : [];
$signature = null;

if (!empty($headers)) {
    // header names can vary in casing
    foreach ($headers as $k => $v) {
        if (strtolower($k) === 'x-paystack-signature' || strtolower($k) === 'http_x_paystack_signature') {
            $signature = $v;
            break;
        }
    }
}
// Fallback to $_SERVER
if (!$signature && isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'])) {
    $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'];
}

if (!$signature) {
    // No signature — reject
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing signature']);
    exit;
}

// Ensure $paystack_secret_key exists
if (!isset($paystack_secret_key) || empty($paystack_secret_key)) {
    // You should set $paystack_secret_key in backend/config/db.php or a config file
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Paystack secret key not configured']);
    exit;
}

// Verify signature
$computed = hash_hmac('sha512', $payload, $paystack_secret_key);
if (!hash_equals($computed, $signature)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

// Decode payload
$body = json_decode($payload, true);
if ($body === null) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

// Extract event and data
$event = $body['event'] ?? '';
$data = $body['data'] ?? null;

// For Paystack transaction events, data will contain 'reference' and 'amount' etc.
$reference = $data['reference'] ?? null;
$transaction_id = $data['id'] ?? null;
$amount = isset($data['amount']) ? (int)$data['amount'] : null; // in kobo/pesewas
$status = $data['status'] ?? null;

// Insert raw payload into payment_logs for audit (best-effort; do not block)
try {
    $logStmt = $pdo->prepare("
        INSERT INTO payment_logs (order_id, transaction_reference, event_type, payload_json, status)
        VALUES (?, ?, ?, ?, ?)
    ");
    // we do not yet know order_id; insert with NULL then update later if needed
    $logStmt->execute([null, $reference, $event, $payload, $status ?? '']);
    $payment_log_id = $pdo->lastInsertId();
} catch (Exception $e) {
    // continue — logging failure shouldn't break webhook handling
    $payment_log_id = null;
}

// We only handle successful charges here.
// Also handle 'transaction.verify' or related events if Paystack uses them.
// Paystack sends 'charge.success' or 'transfer.success' etc. We'll focus on charge.success or transaction.success.
$handled_events = ['charge.success', 'charge.successful', 'transaction.success', 'transfer.success'];

if (!in_array($event, $handled_events)) {
    // For other events, respond 200 so Paystack doesn't retry
    http_response_code(200);
    echo json_encode(['status' => 'ignored', 'message' => 'Event ignored']);
    exit;
}

// Find order by order_number = reference
try {
    $orderStmt = $pdo->prepare("SELECT id, total_amount, payment_status, order_status FROM orders WHERE order_number = ? LIMIT 1");
    $orderStmt->execute([$reference]);
    $order = $orderStmt->fetch();
} catch (Exception $e) {
    // update payment_logs if possible
    if ($payment_log_id) {
        $upd = $pdo->prepare("UPDATE payment_logs SET order_id = ?, status = ? WHERE id = ?");
        $upd->execute([null, 'order_lookup_failed', $payment_log_id]);
    }
    http_response_code(200); // respond 200 to avoid retries but log the issue elsewhere
    echo json_encode(['status' => 'error', 'message' => 'Order lookup failed']);
    exit;
}

if (!$order) {
    // no order found; log and exit 200
    if ($payment_log_id) {
        $upd = $pdo->prepare("UPDATE payment_logs SET status = ? WHERE id = ?");
        $upd->execute(['order_not_found', $payment_log_id]);
    }
    http_response_code(200);
    echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    exit;
}

$order_id = (int)$order['id'];

// Optional: verify amount matches (order.total_amount in N, Paystack amount in pesewas)
if ($amount !== null) {
    $expected_pesewas = (int) round($order['total_amount'] * 100);
    if ($amount != $expected_pesewas) {
        // amount mismatch — log it but still process cautiously
        $note = "Amount mismatch: expected {$expected_pesewas}, got {$amount}";
        $updLog = $pdo->prepare("UPDATE payment_logs SET status = ?, payload_json = ? WHERE id = ?");
        if ($payment_log_id) $updLog->execute(['amount_mismatch', $payload, $payment_log_id]);
        // We choose to continue but mark failure if desired. For now, continue to mark paid.
    }
}

// Use transaction to atomically update order, payment_logs and notifications
try {
    $pdo->beginTransaction();

    // Update order: payment_status -> paid, order_status -> confirmed (if pending)
    $updateOrderStmt = $pdo->prepare("
        UPDATE orders
        SET payment_status = 'paid',
            order_status = CASE
                WHEN order_status = 'pending' THEN 'confirmed'
                ELSE order_status
            END,
            transaction_id = :txid,
            transaction_reference = :reference,
            updated_at = NOW()
        WHERE id = :order_id
    ");
    $updateOrderStmt->execute([
        ':txid' => $transaction_id,
        ':reference' => $reference,
        ':order_id' => $order_id
    ]);

    // Update payment_log to attach order_id and success status
    if ($payment_log_id) {
        $attachLog = $pdo->prepare("UPDATE payment_logs SET order_id = ?, status = ? WHERE id = ?");
        $attachLog->execute([$order_id, 'success', $payment_log_id]);
    }

    // Fetch involved vendors to notify
    $vendorStmt = $pdo->prepare("SELECT DISTINCT vendor_id FROM order_items WHERE order_id = ?");
    $vendorStmt->execute([$order_id]);
    $vendors = $vendorStmt->fetchAll();

    // Notify customer (in-app)
    $notifyUserStmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $msgUser = "Payment received for order {$reference}. Your order is being processed.";
    $notifyUserStmt->execute([$order['user_id'], $msgUser]);

    // Notify each vendor involved
    $notifyVendorStmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    if (!empty($vendors)) {
        $vendorUsersStmt = $pdo->prepare("
            SELECT u.id AS user_id
            FROM vendors vd
            JOIN users u ON vd.user_id = u.id
            WHERE vd.id = ?
            LIMIT 1
        ");
        foreach ($vendors as $vRow) {
            $vid = (int)$vRow['vendor_id'];
            $vendorUsersStmt->execute([$vid]);
            $vendorUser = $vendorUsersStmt->fetch();
            if ($vendorUser) {
                $msgVendor = "Order {$reference} has been paid. Please check your orders.";
                $notifyVendorStmt->execute([$vendorUser['user_id'], $msgVendor]);
            }
        }
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    // update payment_logs to indicate failure
    if ($payment_log_id) {
        $upd = $pdo->prepare("UPDATE payment_logs SET status = ? WHERE id = ?");
        $upd->execute(['update_failed', $payment_log_id]);
    }
    http_response_code(200); // acknowledge to Paystack
    echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    exit;
}

// All done - respond 200 OK
http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Webhook processed']);
exit;
