<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | Thoughtful</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #F5F3FF; /* lavender */
      color: #000000; /* black */
    }
    header {
      background-color: #9966CC; /* amethyst */
      color: white;
      padding: 20px;
      text-align: center;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(153, 102, 204, 0.2);
    }
    h2 {
      color: #9966CC;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }
    button {
      margin-top: 20px;
      background-color: #9966CC;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #7A4EB5;
    }
    footer {
      text-align: center;
      padding: 20px;
      background-color: #000000;
      color: white;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<header>
  <h1>Contact Thoughtful</h1>
  <p>We’re here to help make gifting easier and more meaningful.</p>
</header>

<div class="container">
  <h2>Send Us a Message</h2>
  <form action="send_message.php" method="POST">
    <label for="name">Your Name</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Your Email</label>
    <input type="email" id="email" name="email" required>

    <label for="subject">Subject</label>
    <input type="text" id="subject" name="subject">

    <label for="message">Message</label>
    <textarea id="message" name="message" rows="6" required></textarea>

    <button type="submit">Send Message</button>
  </form>
</div>

<footer>
  &copy; 2025 ThoughtfulGift.com | <a href="/privacy-policy" style="color:white;">Privacy Policy</a> | <a href="/terms-of-use" style="color:white;">Terms of Use</a>
</footer>

</body>
</html>
