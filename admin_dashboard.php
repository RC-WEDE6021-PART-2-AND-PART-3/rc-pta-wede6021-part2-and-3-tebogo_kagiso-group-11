<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'DBConn.php';

// Approve user
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE tbluser SET status='approved' WHERE user_id=$id");
    header("Location: admin_dashboard.php?msg=User approved");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM tbluser WHERE user_id=$id");
    header("Location: admin_dashboard.php?msg=User deleted");
    exit();
}

// Update user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = intval($_POST['user_id']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    mysqli_query($conn, "UPDATE tbluser SET fullname='$fullname', email='$email' WHERE user_id=$id");
    header("Location: admin_dashboard.php?msg=User updated");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM tbluser ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Pastimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F2EADF; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #FEFEFE; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2D674E; margin-bottom: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .back-btn { background: #2D674E; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block; }
        .admin-nav { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
        .admin-nav a { background: #2D674E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .admin-nav a:hover { background: #1A4A38; }
        .admin-nav a.active { background: #1A4A38; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #9AB0A6; }
        th { background: #2D674E; color: white; }
        .approve { background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .delete { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .edit { background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; cursor: pointer; }
        .pending { background: #ffc107; color: #1A1B1B; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .approved { background: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        footer { margin-top: 30px; text-align: center; color: #6B887C; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #2D674E; color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-card h3 { font-size: 32px; }
        .stat-card p { font-size: 14px; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Admin Dashboard</h2>
            <div>
                <a href="index.php" class="back-btn"><- Back to Website</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="admin-nav">
            <a href="admin_dashboard.php" class="active">Users</a>
            <a href="admin_products.php">Products</a>
            <a href="admin_requests.php">Seller Requests</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_messages.php">Messages</a>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="message"> <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <?php
            $total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbluser"));
            $pending_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbluser WHERE status='pending'"));
            $total_products = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblclothes"));
            $total_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblorders"));
            ?>
            <div class="stat-card"><h3><?php echo $total_users; ?></h3><p>Total Users</p></div>
            <div class="stat-card"><h3><?php echo $pending_users; ?></h3><p>Pending Users</p></div>
            <div class="stat-card"><h3><?php echo $total_products; ?></h3><p>Products</p></div>
            <div class="stat-card"><h3><?php echo $total_orders; ?></h3><p>Orders</p></div>
        </div>
        
        <h3>Customer List</h3>
        <table>
            <thead>
                <tr><th>ID</th><th>Full Name</th><th>Email</th><th>Username</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><span class="<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <a href="?approve=<?php echo $row['user_id']; ?>" class="approve" onclick="return confirm('Approve this user?')">Approve</a>
                    <?php endif; ?>
                    <a href="#" onclick="editUser(<?php echo $row['user_id']; ?>, '<?php echo addslashes($row['fullname']); ?>', '<?php echo addslashes($row['email']); ?>')" class="edit">Edit</a>
                    <a href="?delete=<?php echo $row['user_id']; ?>" class="delete" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <footer>
            <p>2024 Pastimes Admin Panel</p>
        </footer>
    </div>
    
    <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#FEFEFE; padding:25px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.3); z-index:1000; width:350px;">
        <h3 style="color:#2D674E; margin-bottom:15px;">Edit User</h3>
        <form method="POST">
            <input type="hidden" name="user_id" id="edit_id">
            <label>Full Name:</label><br>
            <input type="text" name="fullname" id="edit_fullname" style="width:100%; padding:8px; margin:10px 0; border:1px solid #9AB0A6; border-radius:5px;" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" id="edit_email" style="width:100%; padding:8px; margin:10px 0; border:1px solid #9AB0A6; border-radius:5px;" required><br>
            <button type="submit" name="update" style="background:#2D674E; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Update</button>
            <button type="button" onclick="document.getElementById('editModal').style.display='none'" style="background:gray; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; margin-left:10px;">Cancel</button>
        </form>
    </div>
    
    <script>
        function editUser(id, name, email) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_fullname').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('editModal').style.display = 'block';
        }
    </script>
</body>
</html>