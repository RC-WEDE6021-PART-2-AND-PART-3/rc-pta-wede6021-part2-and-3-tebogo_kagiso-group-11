<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'DBConn.php';

$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dashboard - Pastimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F2EADF; }
        .top-bar { background-color: #2D674E; color: #FEFEFE; padding: 8px; text-align: center; font-size: 14px; }
        header { background-color: #2D674E; color: #FEFEFE; padding: 20px; text-align: center; }
        nav { background-color: #2D674E; padding: 15px; text-align: center; }
        nav a { color: #FEFEFE; text-decoration: none; margin: 0 15px; padding: 8px 16px; border-radius: 5px; }
        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        .dashboard-box { background: #FEFEFE; padding: 30px; border-radius: 10px; }
        h2 { color: #2D674E; margin-bottom: 20px; }
        .welcome-card { background: #F2EADF; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .stat-card { background: #2D674E; color: white; padding: 20px; border-radius: 10px; flex: 1; text-align: center; }
        .stat-card h3 { font-size: 32px; }
        .btn { background: #2D674E; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; text-align: center; padding: 40px 20px 20px 20px; margin-top: 50px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-section h4 { color: #FEFEFE; margin-bottom: 15px; font-size: 18px; }
        .footer-section p { margin: 8px 0; font-size: 14px; line-height: 1.5; }
        .footer-section a { color: #9AB0A6; text-decoration: none; display: block; margin: 8px 0; font-size: 14px; }
        .footer-bottom { text-align: center; padding-top: 20px; margin-top: 20px; border-top: 1px solid #2D674E; font-size: 12px; }
    </style>
</head>
<body>
    <div class="top-bar">Free delivery on orders over R500</div>
    <header><h1>PASTIMES</h1><p>Pre-loved branded clothing</p></header>
    
    <nav>
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="sell.php">Sell</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <div class="dashboard-box">
            <h2>My Dashboard</h2>
            <div class="welcome-card">
                <h3>Welcome back, <?php echo htmlspecialchars($fullname); ?>!</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
            <div class="stats">
                <div class="stat-card"><h3>0</h3><p>Orders</p></div>
                <div class="stat-card"><h3><?php echo $cart_count; ?></h3><p>Cart Items</p></div>
                <div class="stat-card"><h3>0</h3><p>Sold</p></div>
            </div>
            <a href="shop.php" class="btn">Shop Now</a>
            <a href="cart.php" class="btn">View Cart</a>
        </div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Pastimes</h4>
                <p>Kempton Park's marketplace for pre-loved branded clothing.</p>
                <p><strong>Email:</strong> kayge_tebogo.pastime@gmail.com</p>
                <p><strong>Phone:</strong> 067 876 7564 / 075 675 6543</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="sell.php">Sell</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Pastimes | Tebogo Mabusela & Kagiso Maputla</p>
            <p>Based in Kempton Park | South Africa</p>
        </div>
    </footer>
</body>
</html>