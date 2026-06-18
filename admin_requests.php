<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'DBConn.php';

// Approve request - add to products
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $result = mysqli_query($conn, "SELECT * FROM tblseller_requests WHERE request_id=$id");
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $sql = "INSERT INTO tblclothes (name, brand, price, category, size, condition_status, image_path) 
                VALUES ('{$row['product_name']}', '{$row['brand']}', '{$row['price']}', 
                        '{$row['category']}', '{$row['size']}', '{$row['item_condition']}', '{$row['image_path']}')";
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn, "UPDATE tblseller_requests SET status='approved' WHERE request_id=$id");
            header("Location: admin_requests.php?msg=Request approved & product added");
        } else {
            header("Location: admin_requests.php?error=" . mysqli_error($conn));
        }
    }
    exit();
}

// Reject request
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    mysqli_query($conn, "UPDATE tblseller_requests SET status='rejected' WHERE request_id=$id");
    header("Location: admin_requests.php?msg=Request rejected");
    exit();
}

// Delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = mysqli_query($conn, "SELECT image_path FROM tblseller_requests WHERE request_id=$id");
    $row = mysqli_fetch_assoc($result);
    if ($row && $row['image_path'] && file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }
    mysqli_query($conn, "DELETE FROM tblseller_requests WHERE request_id=$id");
    header("Location: admin_requests.php?msg=Request deleted");
    exit();
}

$requests = mysqli_query($conn, "SELECT * FROM tblseller_requests ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Requests - Pastimes</title>
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
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #9AB0A6; }
        th { background: #2D674E; color: white; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .approve-btn { background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .reject-btn { background: #ffc107; color: #1A1B1B; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .pending { background: #ffc107; color: #1A1B1B; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .approved { background: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .rejected { background: #dc3545; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        footer { margin-top: 30px; text-align: center; color: #6B887C; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Seller Requests</h2>
            <div>
                <a href="index.php" class="back-btn"><- Back to Website</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="admin-nav">
            <a href="admin_dashboard.php">Users</a>
            <a href="admin_products.php">Products</a>
            <a href="admin_requests.php" class="active">Seller Requests</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_messages.php">Messages</a>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="message"> <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="error"> <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <h3>Seller Listing Requests</h3>
        <p style="color:#6B887C; margin-bottom:15px;">Approve or reject seller requests. Approved items will be added to the product catalog.</p>
        
        <?php if (mysqli_num_rows($requests) == 0): ?>
            <p style="text-align:center; padding:40px; color:#6B887C;">No seller requests found.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr><th>Image</th><th>Seller</th><th>Product</th><th>Brand</th><th>Price</th><th>Category</th><th>Size</th><th>Condition</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($requests)): ?>
            <tr>
                <td>
                    <?php if ($row['image_path'] && file_exists($row['image_path'])): ?>
                        <img src="<?php echo $row['image_path']; ?>" class="product-img">
                    <?php else: ?>
                        <img src="https://placehold.co/60x60?text=No+Image" class="product-img">
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['brand']); ?></td>
                <td>R<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['size']); ?></td>
                <td><?php echo htmlspecialchars($row['item_condition']); ?></td>
                <td><span class="<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <a href="?approve=<?php echo $row['request_id']; ?>" class="approve-btn" onclick="return confirm('Approve this listing?')">Approve</a>
                        <a href="?reject=<?php echo $row['request_id']; ?>" class="reject-btn" onclick="return confirm('Reject this listing?')">Reject</a>
                    <?php endif; ?>
                    <a href="?delete=<?php echo $row['request_id']; ?>" class="delete-btn" onclick="return confirm('Delete this request?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <footer>2024 Pastimes Admin Panel</footer>
    </div>
</body>
</html>