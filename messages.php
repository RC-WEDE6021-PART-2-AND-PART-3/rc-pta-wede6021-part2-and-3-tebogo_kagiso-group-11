<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'DBConn.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $message_text = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    $username = $_SESSION['username'];
    
    if (empty($subject) || empty($message_text)) {
        $message = "Please fill in all fields!";
        $messageType = "error";
    } else {
        $sql = "INSERT INTO tblmessages (sender_username, receiver_username, subject, message, is_read) 
                VALUES ('$username', 'admin', '$subject', '$message_text', 0)";
        if (mysqli_query($conn, $sql)) {
            $message = "Message sent successfully! Admin will respond within 24 hours.";
            $messageType = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $messageType = "error";
        }
    }
}

// Get user's messages
$username = $_SESSION['username'];
$user_messages = mysqli_query($conn, "SELECT * FROM tblmessages WHERE sender_username='$username' OR receiver_username='$username' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - Pastimes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F2EADF; }
        .top-bar { background-color: #2D674E; color: #FEFEFE; padding: 8px; text-align: center; font-size: 14px; }
        .help-bar { background-color: #F2EADF; padding: 8px 20px; text-align: right; font-size: 12px; }
        .help-bar a { color: #2D674E; text-decoration: none; margin-left: 20px; }
        header { background-color: #FEFEFE; padding: 20px; text-align: center; border-bottom: 1px solid #9AB0A6; }
        .logo h1 { color: #2D674E; font-size: 28px; }
        .logo p { color: #6B887C; font-size: 12px; }
        nav { background-color: #2D674E; padding: 15px; text-align: center; }
        nav a { color: #FEFEFE; text-decoration: none; margin: 0 15px; padding: 8px 16px; border-radius: 5px; transition: background 0.3s; }
        nav a:hover { background-color: #1A4A38; }
        .container { max-width: 800px; margin: 50px auto; padding: 0 20px; }
        .message-box { background: #FEFEFE; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2D674E; margin-bottom: 20px; border-bottom: 2px solid #2D674E; padding-bottom: 10px; display: inline-block; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #1A1B1B; }
        input, textarea { width: 100%; padding: 12px; border: 2px solid #9AB0A6; border-radius: 5px; font-size: 14px; }
        input:focus, textarea:focus { outline: none; border-color: #2D674E; }
        button { background: #2D674E; color: #FEFEFE; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; }
        button:hover { background: #1A4A38; }
        .message-feedback { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .msg-history { margin-top: 30px; }
        .msg-item { background: #F2EADF; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #2D674E; }
        .msg-item .from { font-weight: bold; color: #2D674E; }
        .msg-item .date { font-size: 12px; color: #6B887C; }
        .msg-item .subject { font-weight: bold; margin: 5px 0; }
        .msg-item .body { color: #1A1B1B; }
        .no-msg { text-align: center; color: #6B887C; padding: 20px; }
        .from-admin { border-left-color: #007bff; }
        .from-user { border-left-color: #2D674E; }
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
        <a href="messages.php">Messages</a>
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
        <a href="dashboard.php">Dashboard</a>
        <a href="cart.php">Cart</a>
        <a href="messages.php">Messages</a>
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <div class="message-box">
            <h2>Messages</h2>
            <p style="color: #6B887C; margin-bottom: 20px;">Send a message to admin or view your conversation history.</p>
            
            <?php if ($message): ?>
                <div class="message-feedback <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="messages.php">
                <div class="form-group">
                    <label>Subject *</label>
                    <input type="text" name="subject" placeholder="e.g., Question about my order" required>
                </div>
                <div class="form-group">
                    <label>Message *</label>
                    <textarea name="message" rows="4" placeholder="Type your message here..." required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
            
            <div class="msg-history">
                <h3 style="color: #2D674E; margin-top: 30px; border-top: 2px solid #9AB0A6; padding-top: 20px;">Message History</h3>
                
                <?php if (mysqli_num_rows($user_messages) == 0): ?>
                    <div class="no-msg">No messages yet.</div>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($user_messages)): 
                        $is_from_admin = $row['sender_username'] == 'admin';
                    ?>
                    <div class="msg-item <?php echo $is_from_admin ? 'from-admin' : 'from-user'; ?>">
                        <div class="from"><?php echo $is_from_admin ? 'Admin' : htmlspecialchars($row['sender_username']); ?></div>
                        <div class="date"><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></div>
                        <div class="subject"><?php echo htmlspecialchars($row['subject']); ?></div>
                        <div class="body"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
                    </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="footer-features">
            <div class="feature">Secure Payments</div>
            <div class="feature">Free Delivery over R500</div>
            <div class="feature">Sustainable Fashion</div>
        </div>
        <p>2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p>
        <p>Based in Kempton Park | Serving South Africa</p>
    </footer>
</body>
</html>