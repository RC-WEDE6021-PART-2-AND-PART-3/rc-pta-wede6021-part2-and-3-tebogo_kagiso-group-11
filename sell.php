<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'DBConn.php';

$message = "";
$messageType = "";

// Process sell form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name'] ?? '');
    $brand = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
    $price = mysqli_real_escape_string($conn, $_POST['price'] ?? '');
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    $condition = mysqli_real_escape_string($conn, $_POST['condition'] ?? '');
    $size = mysqli_real_escape_string($conn, $_POST['size'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $image_path = '';
    
    // Handle image upload - using images/ folder
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $image_path = $target_dir . time() . '_' . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        } else {
            $message = "Invalid image format. Only JPG, PNG, GIF, WEBP allowed.";
            $messageType = "error";
        }
    }
    
    if (empty($message)) {
        // Save to database
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        
        $sql = "INSERT INTO tblseller_requests (user_id, username, product_name, brand, price, category, size, item_condition, description, image_path, status) 
                VALUES ('$user_id', '$username', '$product_name', '$brand', '$price', '$category', '$size', '$condition', '$description', '$image_path', 'pending')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Your item has been submitted for review! Admin will approve it shortly.";
            $messageType = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell an Item - Pastimes</title>
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
        
        .container { max-width: 600px; margin: 50px auto; padding: 0 20px; }
        .sell-box { background: #FEFEFE; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        h2 { color: #2D674E; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #2D674E; padding-bottom: 10px; display: inline-block; width: auto; }
        .title-center { text-align: center; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #1A1B1B; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #9AB0A6; border-radius: 5px; font-size: 14px; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #2D674E; }
        input[type="file"] { padding: 8px; }
        
        button { background: #2D674E; color: #FEFEFE; padding: 14px; width: 100%; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; }
        button:hover { background: #1A4A38; }
        
        .message { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .info-text { font-size: 12px; color: #6B887C; margin-top: 8px; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; text-align: center; padding: 30px; margin-top: 50px; }
        .footer-features { display: flex; justify-content: center; gap: 40px; margin-bottom: 20px; flex-wrap: wrap; }
        .feature { text-align: center; }
        
        @media (max-width: 768px) {
            .container { margin: 20px auto; }
        }
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
        <?php if(isset($_SESSION['username'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="cart.php">Cart</a>
            <a href="messages.php">Messages</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">
        <div class="sell-box">
            <div class="title-center">
                <h2>Sell Your Pre-loved Items</h2>
                <p style="color: #6B887C; margin-top: 10px;">Submit your item for review. Admin will approve it within 24 hours.</p>
            </div>
            
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="sell.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="product_name" placeholder="e.g., Vintage Logo Tee" required>
                </div>
                
                <div class="form-group">
                    <label>Brand *</label>
                    <input type="text" name="brand" placeholder="e.g., ELLESSE, REDBAT, NIKE" required>
                </div>
                
                <div class="form-group">
                    <label>Price (R) *</label>
                    <input type="number" name="price" placeholder="e.g., 250" required min="1">
                </div>
                
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="">Select Category</option>
                        <option>Men</option>
                        <option>Women</option>
                        <option>Kids</option>
                        <option>Shoes</option>
                        <option>Accessories</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Condition *</label>
                    <select name="condition" required>
                        <option value="">Select Condition</option>
                        <option>Like New</option>
                        <option>Excellent</option>
                        <option>Good</option>
                        <option>Fair</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" placeholder="e.g., S, M, L, XL, 42">
                </div>
                
                <div class="form-group">
                    <label>Product Image *</label>
                    <input type="file" name="image" accept="image/*" required>
                    <div class="info-text">Upload a clear photo of your item (JPG, PNG, GIF, WEBP)</div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Describe your item (brand, condition, any flaws, etc.)"></textarea>
                </div>
                
                <button type="submit">Submit for Review</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="index.php" style="color: #2D674E; text-decoration: none;"><- Back to Home</a>
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