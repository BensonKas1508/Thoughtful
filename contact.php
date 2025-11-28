<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Thoughtful</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/contact.css">
  
</head>
<body>
  <!-- Header -->
  <header>
    <div class="header-container">
      <div class="logo">
        <i class="fas fa-gift"></i>
        Thoughtful
      </div>
      <nav>
        <ul>
          <li><a href="/home.php">Home</a></li>
          <li><a href="/products.php">Gifts</a></li>
          <li><a href="/occasions">Occasions</a></li>
          <li><a href="/vendors.php">Vendors</a></li>
          <li><a href="/contact.php" class="active">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Page Header -->
  <section class="page-header">
    <h1>Contact Thoughtful</h1>
    <p>We're here to help make gifting easier and more meaningful. Get in touch with any questions or feedback.</p>
  </section>

  <!-- Contact Content -->
  <div class="contact-container">
    <!-- Contact Information -->
    <div class="contact-info">
      <h2>Get In Touch</h2>
      <p>Have questions about our gifts, delivery options, or need help with an order? We're here to help!</p>
      
      <div class="contact-method">
        <i class="fas fa-envelope"></i>
        <div class="contact-details">
          <h3>Email Us</h3>
          <p>support@thoughtfulgift.com</p>
          <p>We'll respond within 24 hours</p>
        </div>
      </div>
      
      <div class="contact-method">
        <i class="fas fa-phone"></i>
        <div class="contact-details">
          <h3>Call Us</h3>
          <p>1-800-THOUGHT (1-800-868-4488)</p>
          <p>Mon-Fri: 9am-6pm EST</p>
        </div>
      </div>
      
      <div class="contact-method">
        <i class="fas fa-comments"></i>
        <div class="contact-details">
          <h3>Live Chat</h3>
          <p>Available during business hours</p>
          <p>Look for the chat icon in the bottom right</p>
        </div>
      </div>
      
      <div class="contact-method">
        <i class="fas fa-map-marker-alt"></i>
        <div class="contact-details">
          <h3>Visit Us</h3>
          <p>123 Gift Avenue, Suite 500</p>
          <p>New York, NY 10001</p>
        </div>
      </div>
    </div>
    
    <!-- Contact Form -->
    <div class="contact-form">
      <h2>Send Us a Message</h2>
      <form action="send_message.php" method="POST">
        <div class="form-group">
          <label for="name">Your Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
          <label for="email">Your Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="subject">Subject</label>
          <select id="subject" name="subject">
            <option value="">Select a topic</option>
            <option value="general">General Inquiry</option>
            <option value="order">Order Issue</option>
            <option value="delivery">Delivery Question</option>
            <option value="product">Product Information</option>
            <option value="vendor">Vendor Inquiry</option>
            <option value="feedback">Feedback</option>
            <option value="other">Other</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="6" placeholder="Tell us how we can help..." required></textarea>
        </div>
        
        <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Send Message</button>
      </form>
    </div>
  </div>
  
  <!-- FAQ Section -->
  <section class="faq-section">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-container">
      <div class="faq-item">
        <h3>How long does delivery take?</h3>
        <p>Most gifts are delivered within 3-5 business days. Express delivery options are available at checkout.</p>
      </div>
      
      <div class="faq-item">
        <h3>Can I modify or cancel my order?</h3>
        <p>Orders can be modified or cancelled within 1 hour of placement. Contact us immediately for assistance.</p>
      </div>
      
      <div class="faq-item">
        <h3>Do you offer gift wrapping?</h3>
        <p>Yes! We offer premium gift wrapping for an additional fee. Select this option during checkout.</p>
      </div>
      
      <div class="faq-item">
        <h3>What is your return policy?</h3>
        <p>We accept returns within 30 days of delivery for unused items in original packaging. Some personalized items may be non-returnable.</p>
      </div>
    </div>
  </section>
</body>
</html>