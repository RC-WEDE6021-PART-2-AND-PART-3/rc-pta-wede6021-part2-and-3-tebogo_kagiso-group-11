<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'DBConn.php';

// Mark as read
if (isset($_GET['read'])) {
    $id = intval($_GET['read']);
    mysqli_query($conn, "UPDATE tblmessages SET is_read=1 WHERE message_id=$id");
    header("Location: admin_messages.php");
    exit();
}

// Delete message
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM tblmessages WHERE message_id=$id");
    header("Location: admin_messages.php?msg=Message deleted");
    exit();
}

// Reply to message (simple - saves as new message from admin)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message_text = mysqli_real_escape_string($conn, $_POST['message']);
    
    $sql = "INSERT INTO tblmessages (sender_username, receiver_username, subject, message, is_read) 
            VALUES ('admin', '$receiver', 'RE: $subject', '$message_text', 0)";
    mysqli_query($conn, $sql);
    header("Location: admin_messages.php?msg=Reply sent");
    exit();
}

$messages = mysqli_query($conn, "SELECT * FROM tblmessages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Messages - Pastimes</title>
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
        .unread { font-weight: bold; background: #F2EADF; }
        .read-btn { background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .reply-btn { background: #2D674E; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; cursor: pointer; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        footer { margin-top: 30px; text-align: center; color: #6B887C; }
        .modal { display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#FEFEFE; padding:25px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.3); z-index:1000; width:500px; }
        .modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; }
        .modal input, .modal textarea { width:100%; padding:10px; margin:5px 0 15px 0; border:2px solid #9AB0A6; border-radius:5px; }
        .modal button { padding:10px 20px; border:none; border-radius:5px; cursor:pointer; }
        .no-messages { text-align:center; padding:40px; color:#6B887C; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Messages</h2>
            <div>
                <a href="index.php" class="back-btn"><- Back to Website</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="admin-nav">
            <a href="admin_dashboard.php">Users</a>
            <a href="admin_products.php">Products</a>
            <a href="admin_requests.php">Seller Requests</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_messages.php" class="active">Messages</a>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="message"> <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        
        <h3>All Messages</h3>
        
        <?php if (mysqli_num_rows($messages) == 0): ?>
            <div class="no-messages">No messages found.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr><th>From</th><th>Subject</th><th>Message</th><th>Status</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($messages)): 
                $is_unread = $row['is_read'] == 0 && $row['sender_username'] != 'admin';
            ?>
            <tr class="<?php echo $is_unread ? 'unread' : ''; ?>">
                <td><?php echo htmlspecialchars($row['sender_username']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo substr(htmlspecialchars($row['message']), 0, 50) . (strlen($row['message']) > 50 ? '...' : ''); ?></td>
                <td><?php echo $row['is_read'] ? 'Read' : 'Unread'; ?></td>
                <td><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></td>
                <td>
                    <?php if (!$row['is_read'] && $row['sender_username'] != 'admin'): ?>
                        <a href="?read=<?php echo $row['message_id']; ?>" class="read-btn">Mark Read</a>
                    <?php endif; ?>
                    <a href="#" onclick="replyMessage('<?php echo $row['sender_username']; ?>', '<?php echo addslashes($row['subject']); ?>')" class="reply-btn">Reply</a>
                    <a href="?delete=<?php echo $row['message_id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <footer>2024 Pastimes Admin Panel</footer>
    </div>
    
    <!-- Reply Modal -->
    <div class="modal-overlay" id="replyOverlay" onclick="closeReply()"></div>
    <div class="modal" id="replyModal">
        <h3 style="color:#2D674E;">Reply to Message</h3>
        <form method="POST">
            <input type="hidden" name="receiver" id="reply_receiver">
            <label>Subject</label>
            <input type="text" name="subject" id="reply_subject" required>
            <label>Message</label>
            <textarea name="message" rows="4" required></textarea>
            <div style="margin-top:15px;">
                <button type="submit" name="reply" style="background:#2D674E; color:white;">Send Reply</button>
                <button type="button" onclick="closeReply()" style="background:gray; color:white; margin-left:10px;">Cancel</button>
            </div>
        </form>
    </div>
    
    <script>
        function replyMessage(username, subject) {
            document.getElementById('reply_receiver').value = username;
            document.getElementById('reply_subject').value = 'RE: ' + subject;
            document.getElementById('replyModal').style.display = 'block';
            document.getElementById('replyOverlay').style.display = 'block';
        }
        function closeReply() {
            document.getElementById('replyModal').style.display = 'none';
            document.getElementById('replyOverlay').style.display = 'none';
        }
    </script>
</body>
</html>