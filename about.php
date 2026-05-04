<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pastimes</title>
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
        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        .about-box { background: #FEFEFE; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2D674E; margin-bottom: 20px; border-bottom: 2px solid #2D674E; padding-bottom: 10px; display: inline-block; }
        .about-content { margin-top: 30px; }
        .about-content p { color: #1A1B1B; line-height: 1.8; margin-bottom: 20px; }
        .mission-box { background: #F2EADF; padding: 25px; border-radius: 10px; margin: 30px 0; text-align: center; }
        .mission-box h3 { color: #2D674E; margin-bottom: 15px; }
        .values { display: flex; gap: 30px; margin-top: 30px; flex-wrap: wrap; }
        .value-card { flex: 1; background: #F2EADF; padding: 20px; border-radius: 10px; text-align: center; min-width: 150px; }
        .value-card .icon { font-size: 40px; display: block; margin-bottom: 10px; }
        .value-card h4 { color: #2D674E; margin-bottom: 10px; }
        .value-card p { color: #6B887C; font-size: 14px; }
        .location-box { background: #2D674E; color: white; padding: 20px; border-radius: 10px; margin-top: 30px; text-align: center; }
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
        <div class="about-box">
            <h2>About Pastimes</h2>
            <div class="about-content">
                <p><strong>Pastimes</strong> is Kempton Park's premier marketplace for pre-loved branded clothing. Founded in 2024, we're on a mission to make sustainable fashion accessible, affordable, and stylish for everyone in South Africa.</p>
                
                <p>We believe that great style shouldn't come at the cost of our planet. Every pre-loved item sold on Pastimes gives clothing a second life, reducing waste and promoting a circular fashion economy.</p>
                
                <div class="mission-box">
                    <h3>🌍 Our Mission</h3>
                    <p>To create a community where fashion lovers can buy and sell quality pre-owned clothing, making sustainable style the norm rather than the exception across South Africa.</p>
                </div>
                
                <h3>Our Values</h3>
                <div class="values">
                    <div class="value-card">
                        <span class="icon">♻️</span>
                        <h4>Sustainability</h4>
                        <p>Reducing fashion waste one item at a time</p>
                    </div>
                    <div class="value-card">
                        <span class="icon">🤝</span>
                        <h4>Community</h4>
                        <p>Building a trusted marketplace for all</p>
                    </div>
                    <div class="value-card">
                        <span class="icon">💎</span>
                        <h4>Quality</h4>
                        <p>Curating only the best pre-loved items</p>
                    </div>
                    <div class="value-card">
                        <span class="icon">💰</span>
                        <h4>Affordability</h4>
                        <p>Amazing prices for everyone</p>
                    </div>
                </div>
                
                <div class="location-box">
                    <p><strong>📍 Based in Kempton Park, Gauteng</strong></p>
                    <p>Serving all of South Africa with quality pre-loved branded clothing</p>
                </div>
                
                <p style="margin-top: 30px;"><strong>📧 Email:</strong> kayge_tebogo.pastime@gmail.com</p>
                <p><strong>📞 Phone:</strong> 067 876 7564 / 075 675 6543</p>
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
        <p>Based in Kempton Park | Serving South Africa</p>
    </footer>
</body>
</html>