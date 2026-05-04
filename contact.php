<?php
session_start();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message = "Please fill in all fields!";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address!";
        $messageType = "error";
    } else {
        // Save to file
        $filename = "contact_messages.txt";
        $data = date('Y-m-d H:i:s') . "|" . $name . "|" . $email . "|" . $subject . "|" . $message_text . "\n";
        
        $file = fopen($filename, "a");
        if ($file) {
            fwrite($file, $data);
            fclose($file);
            $message = "Thank you for contacting us! We'll get back to you within 24 hours.";
            $messageType = "success";
        } else {
            $message = "Error sending message. Please try again.";
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pastimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #FEFEFE; }
        .top-bar { background-color: #2D674E; color: #FEFEFE; padding: 8px; text-align: center; font-size: 14px; }
        .help-bar { background-color: #F2EADF; padding: 8px 20px; text-align: right; font-size: 12px; }
        .help-bar a { color: #2D674E; text-decoration: none; margin-left: 20px; }
        header { background-color: #FEFEFE; padding: 20px; text-align: center; border-bottom: 1px solid #9AB0A6; }
        .logo h1 { color: #2D674E; font-size: 28px; }
        .logo p { color: #6B887C; font-size: 12px; }
        nav { background-color: #2D674E; padding: 15px; text-align: center; }
        nav a { color: #FEFEFE; text-decoration: none; margin: 0 15px; padding: 8px 16px; border-radius: 5px; transition: background 0.3s; }
        nav a:hover { background-color: #1A4A38; }
        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; display: flex; gap: 40px; flex-wrap: wrap; }
        .contact-form { flex: 2; background: #FEFEFE; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .contact-info { flex: 1; background: #F2EADF; padding: 30px; border-radius: 10px; }
        h2 { color: #2D674E; margin-bottom: 20px; border-bottom: 2px solid #2D674E; padding-bottom: 10px; display: inline-block; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #1A1B1B; }
        input, textarea { width: 100%; padding: 12px; border: 2px solid #9AB0A6; border-radius: 5px; font-size: 14px; transition: border-color 0.3s; }
        input:focus, textarea:focus { outline: none; border-color: #2D674E; }
        button { background: #2D674E; color: #FEFEFE; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; width: 100%; }
        button:hover { background: #1A4A38; }
        .message { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info-item { margin-bottom: 25px; }
        .info-item .icon { font-size: 24px; display: block; margin-bottom: 10px; }
        .info-item h4 { color: #2D674E; margin-bottom: 8px; }
        .info-item p { color: #1A1B1B; line-height: 1.5; }
        .social-links { display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap; }
        .social-links a { color: #2D674E; text-decoration: none; }
        .map-placeholder { background: #9AB0A6; height: 150px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; margin-top: 20px; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; text-align: center; padding: 30px; margin-top: 50px; }
        .footer-features { display: flex; justify-content: center; gap: 40px; margin-bottom: 20px; flex-wrap: wrap; }
        .feature { text-align: center; }
    </style>
</head>
<body>
    <div class="top-bar">Free delivery on orders over R500</div>
    <div class="help-bar">
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="#">Track Order</a>
    </div>
    
    <header>
        <div class="logo">
            <h1>Pastimes</h1>
            <p>Pre-loved branded clothing</p>
        </div>
    </header>
    
    <nav>
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="sell.php">Sell</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">
        <div class="contact-form">
            <h2>Get in Touch</h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="contact.php">
                <div class="form-group">
                    <label>Your Name *</label>
                    <input type="text" name="name" required value="<?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" required value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Subject *</label>
                    <input type="text" name="subject" required placeholder="e.g., Question about order, Selling inquiry, etc.">
                </div>
                
                <div class="form-group">
                    <label>Message *</label>
                    <textarea name="message" rows="5" required placeholder="Please provide details about your inquiry..."></textarea>
                </div>
                
                <button type="submit">Send Message</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h2>Contact Info</h2>
            
            <div class="info-item">
                <div class="icon">📍</div>
                <h4>Visit Us</h4>
                <p>Kempton Park<br>Gauteng, South Africa</p>
            </div>
            
            <div class="info-item">
                <div class="icon">📧</div>
                <h4>Email Us</h4>
                <p>kayge_tebogo.pastime@gmail.com</p>
            </div>
            
            <div class="info-item">
                <div class="icon">📞</div>
                <h4>Call or WhatsApp</h4>
                <p>067 876 7564<br>075 675 6543</p>
                <p style="font-size: 12px; margin-top: 5px;">Mon-Fri: 9am - 5pm</p>
            </div>
            
            <div class="info-item">
                <div class="icon">⏰</div>
                <h4>Store Hours</h4>
                <p>Monday - Friday: 9am - 5pm<br>Saturday: 10am - 2pm<br>Sunday: Closed</p>
            </div>
            
            <div class="social-links">
                <a href="#">📘 Facebook</a>
                <a href="#">📷 Instagram</a>
                <a href="#">🐦 Twitter</a>
                <a href="#">💬 WhatsApp</a>
            </div>
            
            <div class="map-placeholder">
                📍 Located in Kempton Park, Gauteng
            </div>
        </div>
    </div>
    
    <footer>
        <div class="footer-features">
            <div class="feature">🔒 Secure Payments</div>
            <div class="feature">📦 Free Delivery over R500</div>
            <div class="feature">🌿 Sustainable Fashion</div>
        </div>
        <p>© 2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p>
        <p>Serving Kempton Park and all of South Africa</p>
    </footer>
</body>
</html>