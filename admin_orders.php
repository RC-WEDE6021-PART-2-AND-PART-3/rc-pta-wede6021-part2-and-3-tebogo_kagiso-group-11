<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'DBConn.php';

// Update order status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    mysqli_query($conn, "UPDATE tblorders SET status='$status' WHERE order_id=$id");
    header("Location: admin_orders.php?msg=Order status updated");
    exit();
}

// Delete order
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM tblorders WHERE order_id=$id");
    header("Location: admin_orders.php?msg=Order deleted");
    exit();
}

$orders = mysqli_query($conn, "SELECT * FROM tblorders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Orders - Pastimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F2EADF; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #FEFEFE; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        h2 { color: #2D674E; }
        .admin-nav { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
        .admin-nav a { background: #2D674E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .admin-nav a:hover { background: #1A4A38; }
        .admin-nav a.active { background: #1A4A38; }
        .back-btn { background: #2D674E; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #9AB0A6; }
        th { background: #2D674E; color: white; }
        .status-badge { padding: 3px 10px; border-radius: 3px; font-size: 12px; }
        .pending { background: #ffc107; color: #1A1B1B; }
        .processing { background: #17a2b8; color: white; }
        .shipped { background: #007bff; color: white; }
        .delivered { background: #28a745; color: white; }
        .cancelled { background: #dc3545; color: white; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .status-btn { background: #2D674E; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .order-items { font-size: 12px; color: #6B887C; }
        footer { margin-top: 30px; text-align: center; color: #6B887C; }
        .no-orders { text-align:center; padding:40px; color:#6B887C; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Orders Management</h2>
            <div>
                <a href="index.php" class="back-btn"><- Back to Website</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="admin-nav">
            <a href="admin_dashboard.php">Users</a>
            <a href="admin_products.php">Products</a>
            <a href="admin_requests.php">Seller Requests</a>
            <a href="admin_orders.php" class="active">Orders</a>
            <a href="admin_messages.php">Messages</a>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="message"> <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        
        <h3>All Orders</h3>
        
        <?php if (mysqli_num_rows($orders) == 0): ?>
            <div class="no-orders">No orders found.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($orders)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?><br><small><?php echo htmlspecialchars($row['email']); ?></small></td>
                <td class="order-items"><?php echo nl2br(htmlspecialchars($row['items'])); ?></td>
                <td>R<?php echo number_format($row['grand_total'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td>
                    <span class="status-badge <?php echo strtolower($row['status']); ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                </td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td>
                    <select onchange="updateStatus(<?php echo $row['order_id']; ?>, this.value)">
                        <option value="pending" <?php echo $row['status']=='pending'?'selected':''; ?>>Pending</option>
                        <option value="processing" <?php echo $row['status']=='processing'?'selected':''; ?>>Processing</option>
                        <option value="shipped" <?php echo $row['status']=='shipped'?'selected':''; ?>>Shipped</option>
                        <option value="delivered" <?php echo $row['status']=='delivered'?'selected':''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $row['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                    </select>
                    <a href="?delete=<?php echo $row['order_id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <footer>2024 Pastimes Admin Panel</footer>
    </div>
    
    <script>
        function updateStatus(orderId, status) {
            window.location.href = '?status=' + status + '&id=' + orderId;
        }
    </script>
</body>
</html>