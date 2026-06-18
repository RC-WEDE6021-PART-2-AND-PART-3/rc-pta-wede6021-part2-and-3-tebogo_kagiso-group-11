<?php
include 'DBConn.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    if (strlen($password) < 8) {
        $message = "Password must be at least 8 characters!";
    } else {
        $hashedPassword = md5($password);
        $check = mysqli_query($conn, "SELECT * FROM tbluser WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $message = "Username or email already exists!";
        } else {
            $sql = "INSERT INTO tbluser (fullname, email, username, password, status) VALUES ('$fullname', '$email', '$username', '$hashedPassword', 'pending')";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php?registered=success");
                exit();
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Pastimes</title>
    <style>
        body { font-family: Arial; background: #F2EADF; margin: 0; padding: 0; }
        header { background: #2D674E; color: white; padding: 20px; text-align: center; }
        nav { background: #2D674E; padding: 15px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; }
        .container { max-width: 400px; margin: 50px auto; padding: 0 20px; }
        .register-box { background: white; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #9AB0A6; border-radius: 5px; }
        button { background: #2D674E; color: white; padding: 10px; width: 100%; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; text-align: center; }
        h2 { color: #2D674E; text-align: center; }
        footer { background: #1A1B1B; color: #9AB0A6; text-align: center; padding: 20px; margin-top: 50px; }
        .info-text { font-size: 12px; color: #6B887C; text-align: center; margin-top: -5px; margin-bottom: 10px; }
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
        <div class="register-box">
            <h2>Create Account</h2>
            <?php if ($message) echo "<p class='error'>$message</p>"; ?>
            <form method="POST">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="info-text">Password must be at least 8 characters</div>
                <button type="submit">Register</button>
            </form>
            <p style="text-align: center; margin-top: 15px;">Already have an account? <a href="login.php">Login</a></p>
            <p style="text-align: center; margin-top: 10px; font-size: 12px; color: #6B887C;">Accounts require admin approval before login.</p>
        </div>
    </div>
    <footer><p>2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p></footer>
</body>
</html>