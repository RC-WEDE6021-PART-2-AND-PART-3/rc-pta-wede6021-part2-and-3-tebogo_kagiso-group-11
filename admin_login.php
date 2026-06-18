<?php
session_start();
include 'DBConn.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM tbladmin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $message = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pastimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #2D674E 0%, #1A4A38 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .login-container { max-width: 400px; width: 100%; padding: 20px; }
        .login-box { background: #FEFEFE; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .login-box h2 { color: #2D674E; text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #1A1B1B; font-weight: bold; }
        input { width: 100%; padding: 12px; border: 2px solid #9AB0A6; border-radius: 5px; font-size: 16px; }
        input:focus { outline: none; border-color: #2D674E; }
        button { width: 100%; padding: 12px; background-color: #2D674E; color: #FEFEFE; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; }
        button:hover { background-color: #1A4A38; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .back-link { text-align: center; margin-top: 20px; display: block; color: #2D674E; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <?php if ($message): ?>
                <div class="error"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Admin Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Admin Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login as Admin</button>
            </form>
            <a href="index.php" class="back-link">Back to Website</a>
        </div>
    </div>
</body>
</html>