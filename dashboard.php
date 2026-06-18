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
$user_id = $_SESSION['user_id'];
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Get order count for this user
$order_query = mysqli_query($conn, "SELECT COUNT(*) as order_count FROM tblorders WHERE user_id = '$user_id'");
$order_data = mysqli_fetch_assoc($order_query);
$order_count = $order_data['order_count'] ?? 0;

// Get recent orders for this user
$recent_orders = mysqli_query($conn, "SELECT * FROM tblorders WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 5");

// Get total spent by this user
$spent_query = mysqli_query($conn, "SELECT SUM(grand_total) as total_spent FROM tblorders WHERE user_id = '$user_id' AND status != 'cancelled'");
$spent_data = mysqli_fetch_assoc($spent_query);
$total_spent = $spent_data['total_spent'] ?? 0;

// Get pending orders count
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as pending_count FROM tblorders WHERE user_id = '$user_id' AND status = 'pending'");
$pending_data = mysqli_fetch_assoc($pending_query);
$pending_count = $pending_data['pending_count'] ?? 0;

// Get completed orders count (delivered)
$completed_query = mysqli_query($conn, "SELECT COUNT(*) as completed_count FROM tblorders WHERE user_id = '$user_id' AND status = 'delivered'");
$completed_data = mysqli_fetch_assoc($completed_query);
$completed_count = $completed_data['completed_count'] ?? 0;
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
        nav a { color: #FEFEFE; text-decoration: none; margin: 0 15px; padding: 8px 16px; border-radius: 5px; transition: background 0.3s; }
        nav a:hover { background-color: #1A4A38; }
        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        .dashboard-box { background: #FEFEFE; padding: 30px; border-radius: 10px; }
        h2 { color: #2D674E; margin-bottom: 20px; border-bottom: 2px solid #2D674E; padding-bottom: 10px; }
        .welcome-card { background: #F2EADF; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
        .welcome-card h3 { color: #2D674E; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #2D674E; color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-card h3 { font-size: 32px; }
        .stat-card p { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .btn { background: #2D674E; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px; transition: background 0.3s; }
        .btn:hover { background: #1A4A38; }
        .btn-outline { background: transparent; color: #2D674E; border: 2px solid #2D674E; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px; transition: all 0.3s; }
        .btn-outline:hover { background: #2D674E; color: white; }
        
        .recent-orders { margin-top: 30px; }
        .recent-orders h3 { color: #2D674E; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #9AB0A6; }
        th { background: #2D674E; color: white; }
        .status-badge { padding: 3px 10px; border-radius: 3px; font-size: 12px; }
        .pending { background: #ffc107; color: #1A1B1B; }
        .processing { background: #17a2b8; color: white; }
        .shipped { background: #007bff; color: white; }
        .delivered { background: #28a745; color: white; }
        .cancelled { background: #dc3545; color: white; }
        .no-orders { text-align: center; padding: 30px; color: #6B887C; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; text-align: center; padding: 40px 20px 20px 20px; margin-top: 50px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-section h4 { color: #FEFEFE; margin-bottom: 15px; font-size: 18px; }
        .footer-section p { margin: 8px 0; font-size: 14px; line-height: 1.5; }
        .footer-section a { color: #9AB0A6; text-decoration: none; display: block; margin: 8px 0; font-size: 14px; }
        .footer-bottom { text-align: center; padding-top: 20px; margin-top: 20px; border-top: 1px solid #2D674E; font-size: 12px; }
        
        @media (max-width: 768px) {
            .stats { grid-template-columns: 1fr 1fr; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
        }
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
                <div class="stat-card">
                    <h3><?php echo $order_count; ?></h3>
                    <p>Total Orders</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $cart_count; ?></h3>
                    <p>Cart Items</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $pending_count; ?></h3>
                    <p>Pending Orders</p>
                </div>
                <div class="stat-card">
                    <h3>R<?php echo number_format($total_spent, 2); ?></h3>
                    <p>Total Spent</p>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <a href="shop.php" class="btn">Shop Now</a>
                <a href="cart.php" class="btn-outline">View Cart</a>
            </div>
            
            <div class="recent-orders">
                <h3>Recent Orders</h3>
                <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                            <tr>
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td><?php 
                                    $items = explode("\n", $order['items']);
                                    $first_item = $items[0] ?? '';
                                    echo htmlspecialchars($first_item);
                                    if (count($items) > 1) {
                                        echo ' + ' . (count($items) - 1) . ' more';
                                    }
                                ?></td>
                                <td>R<?php echo number_format($order['grand_total'], 2); ?></td>
                                <td><span class="status-badge <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-orders">
                        <p>You haven't placed any orders yet.</p>
                        <p style="margin-top: 10px;"><a href="shop.php" style="color: #2D674E; font-weight: bold;">Start Shopping Now</a></p>
                    </div>
                <?php endif; ?>
            </div>
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