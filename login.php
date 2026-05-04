<?php
session_start();
error_reporting(0);
include 'DBConn.php';

$message = "";
$entered_username = "";
$entered_email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_username = $_POST['username'] ?? '';
    $entered_email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashedPassword = md5($password);
    
    $sql = "SELECT * FROM tbluser WHERE (username = '$entered_username' OR email = '$entered_email') AND password = '$hashedPassword'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user['status'] == 'approved') {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Your account is pending admin approval.";
        }
    } else {
        $message = "Invalid username/email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Pastimes</title>
    <style>
        body { font-family: Arial; background: #F2EADF; margin: 0; padding: 0; }
        header { background: #2D674E; color: white; padding: 20px; text-align: center; }
        nav { background: #2D674E; padding: 15px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; }
        .container { max-width: 400px; margin: 50px auto; padding: 0 20px; }
        .login-box { background: white; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #9AB0A6; border-radius: 5px; }
        button { background: #2D674E; color: white; padding: 10px; width: 100%; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; text-align: center; }
        h2 { color: #2D674E; text-align: center; }
        footer { background: #1A1B1B; color: #9AB0A6; text-align: center; padding: 20px; margin-top: 50px; }
    </style>
</head>
<body>
    <header><h1>PASTIMES</h1><p>Pre-loved branded clothing</p></header>
    <nav>
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
    <div class="container">
        <div class="login-box">
            <h2>Welcome Back</h2>
            <?php if ($message) echo "<p class='error'>$message</p>"; ?>
            <form method="POST" action="login.php">
                <input type="text" name="username" placeholder="Username" value="<?php echo $entered_username; ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo $entered_email; ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p style="text-align: center; margin-top: 15px;">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
    <footer><p>© 2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p></footer>
</body>
</html>