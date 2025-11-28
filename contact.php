<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Thoughtful</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #4b2e83;
      --primary-light: #7A4EB5;
      --secondary: #F4EDFF;
      --accent: #9966CC;
      --dark: #000000;
      --light: #FFFFFF;
      --gray: #6c757d;
      --border-radius: 8px;
      --shadow: 0 4px 12px rgba(153, 102, 204, 0.15);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: var(--dark);
      background-color: var(--secondary);
    }
    
    /* Header */
    header {
      background-color: var(--primary);
      color: var(--light);
      padding: 1.5rem 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .header-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
    }
    
    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      display: flex;
      align-items: center;
    }
    
    .logo i {
      margin-right: 10px;
      color: var(--accent);
    }
    
    nav ul {
      display: flex;
      list-style: none;
    }
    
    nav ul li {
      margin-left: 1.5rem;
    }
    
    nav ul li a {
      color: var(--light);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }
    
    nav ul li a:hover {
      color: var(--accent);
    }
    
    /* Main Content */
    .page-header {
      text-align: center;
      padding: 3rem 0;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      color: var(--light);
    }
    
    .page-header h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
    }
    
    .page-header p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .contact-container {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
    }
    
    @media (max-width: 768px) {
      .contact-container {
        grid-template-columns: 1fr;
      }
    }
    
    .contact-info {
      background-color: var(--light);
      padding: 2rem;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }
    
    .contact-info h2 {
      color: var(--primary);
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
    }
    
    .contact-method {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
    }
    
    .contact-method i {
      background-color: var(--secondary);
      color: var(--primary);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      font-size: 1.2rem;
    }
    
    .contact-details h3 {
      margin-bottom: 0.5rem;
      color: var(--primary);
    }
    
    .contact-form {
      background-color: var(--light);
      padding: 2rem;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }
    
    .contact-form h2 {
      color: var(--primary);
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--primary);
    }
    
    input, textarea, select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: border-color 0.3s, box-shadow 0.3s;
    }
    
    input:focus, textarea:focus, select:focus {
      outline: none;
      border-color: var(--primary-light);
      box-shadow: 0 0 0 3px rgba(75, 46, 131, 0.1);
    }
    
    .btn {
      display: inline-block;
      background-color: var(--primary);
      color: var(--light);
      padding: 12px 24px;
      border: none;
      border-radius: var(--border-radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }
    
    .btn:hover {
      background-color: var(--primary-light);
      transform: translateY(-2px);
    }
    
    .btn i {
      margin-right: 8px;
    }
    
    /* FAQ Section */
    .faq-section {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 20px;
    }
    
    .faq-section h2 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 2rem;
      font-size: 2rem;
    }
    
    .faq-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
    }
    
    .faq-item {
      background-color: var(--light);
      padding: 1.5rem;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }
    
    .faq-item h3 {
      color: var(--primary);
      margin-bottom: 0.5rem;
    }
    
    /* Footer */
    footer {
      background-color: var(--dark);
      color: var(--light);
      padding: 3rem 0 1.5rem;
      margin-top: 3rem;
    }
    
    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
    }
    
    .footer-column h3 {
      color: var(--accent);
      margin-bottom: 1.5rem;
      font-size: 1.2rem;
    }
    
    .footer-column ul {
      list-style: none;
    }
    
    .footer-column ul li {
      margin-bottom: 0.8rem;
    }
    
    .footer-column ul li a {
      color: var(--light);
      text-decoration: none;
      transition: color 0.3s;
    }
    
    .footer-column ul li a:hover {
      color: var(--accent);
    }
    
    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .social-links a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: var(--light);
      text-decoration: none;
      transition: background-color 0.3s, transform 0.3s;
    }
    
    .social-links a:hover {
      background-color: var(--accent);
      transform: translateY(-3px);
    }
    
    .copyright {
      text-align: center;
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.7);
    }
    
    .copyright a {
      color: var(--accent);
      text-decoration: none;
    }
  </style>
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
          <li><a href="/">Home</a></li>
          <li><a href="/gifts">Gifts</a></li>
          <li><a href="/occasions">Occasions</a></li>
          <li><a href="/vendors">Vendors</a></li>
          <li><a href="/contact" class="active">Contact</a></li>
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

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-column">
        <h3>Thoughtful</h3>
        <p>Making gifting easier with thoughtfully curated gifts for every occasion.</p>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
      </div>
      
      <div class="footer-column">
        <h3>Shop</h3>
        <ul>
          <li><a href="/gifts">All Gifts</a></li>
          <li><a href="/occasions">By Occasion</a></li>
          <li><a href="/budget">By Budget</a></li>
          <li><a href="/vendors">Vendors</a></li>
          <li><a href="/gift-cards">Gift Cards</a></li>
        </ul>
      </div>
      
      <div class="footer-column">
        <h3>Help</h3>
        <ul>
          <li><a href="/contact">Contact Us</a></li>
          <li><a href="/faq">FAQ</a></li>
          <li><a href="/shipping">Shipping Info</a></li>
          <li><a href="/returns">Returns</a></li>
          <li><a href="/track-order">Track Order</a></li>
        </ul>
      </div>
      
      <div class="footer-column">
        <h3>Company</h3>
        <ul>
          <li><a href="/about">About Us</a></li>
          <li><a href="/careers">Careers</a></li>
          <li><a href="/press">Press</a></li>
          <li><a href="/blog">Blog</a></li>
          <li><a href="/affiliates">Affiliates</a></li>
        </ul>
      </div>
    </div>
    
    <div class="copyright">
      <p>&copy; 2025 ThoughtfulGift.com | <a href="/privacy-policy">Privacy Policy</a> | <a href="/terms-of-use">Terms of Use</a></p>
    </div>
  </footer>
</body>
</html>