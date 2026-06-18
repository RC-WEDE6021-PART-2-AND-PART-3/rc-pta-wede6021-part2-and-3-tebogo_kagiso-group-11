<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Redirect to cart if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
include 'DBConn.php';

// Calculate total
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
    $subtotal += $item['price'] * $quantity;
}

$delivery_fee = ($subtotal >= 500) ? 0 : 50;
$grand_total = $subtotal + $delivery_fee;

$message = "";
$messageType = "";
$order_success = false;

// Process checkout form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';
    
    // Validation
    if (empty($fullname) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($postal_code) || empty($payment_method)) {
        $message = "Please fill in all fields!";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address!";
        $messageType = "error";
    } elseif (!preg_match('/^[0-9]{10}$/', preg_replace('/[^0-9]/', '', $phone))) {
        $message = "Please enter a valid phone number (10 digits)!";
        $messageType = "error";
    } else {
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        
        // Build items string
        $items_str = "";
        foreach ($_SESSION['cart'] as $item) {
            $qty = isset($item['quantity']) ? $item['quantity'] : 1;
            $items_str .= $item['name'] . " (" . $item['brand'] . ") x" . $qty . " - R" . ($item['price'] * $qty) . "\n";
        }
        
        $sql = "INSERT INTO tblorders (user_id, username, fullname, email, phone, address, city, postal_code, payment_method, items, subtotal, delivery_fee, grand_total, status) 
                VALUES ('$user_id', '$username', '$fullname', '$email', '$phone', '$address', '$city', '$postal_code', '$payment_method', '$items_str', '$subtotal', '$delivery_fee', '$grand_total', 'pending')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['cart'] = [];
            $order_success = true;
        } else {
            $message = "Error processing order: " . mysqli_error($conn);
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
    <title>Checkout - Pastimes</title>
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
        .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; display: flex; gap: 40px; flex-wrap: wrap; }
        
        .checkout-form { flex: 2; background: #FEFEFE; padding: 30px; border-radius: 10px; }
        .order-summary { flex: 1.5; background: #F2EADF; padding: 30px; border-radius: 10px; height: fit-content; position: sticky; top: 20px; }
        
        h2 { color: #2D674E; margin-bottom: 20px; border-bottom: 2px solid #2D674E; padding-bottom: 10px; display: inline-block; }
        h3 { color: #2D674E; margin-bottom: 15px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #1A1B1B; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #9AB0A6; border-radius: 5px; font-size: 14px; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #2D674E; }
        
        .row { display: flex; gap: 15px; flex-wrap: wrap; }
        .row .form-group { flex: 1; }
        
        .payment-methods { margin: 20px 0; }
        .payment-option { display: flex; align-items: center; padding: 15px; border: 2px solid #9AB0A6; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.3s; }
        .payment-option:hover { border-color: #2D674E; background: #F2EADF; }
        .payment-option.selected { border-color: #2D674E; background: #F2EADF; }
        .payment-option input { width: auto; margin-right: 15px; }
        .payment-option label { margin: 0; cursor: pointer; flex: 1; }
        
        button { background: #2D674E; color: #FEFEFE; padding: 14px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; width: 100%; margin-top: 20px; }
        button:hover { background: #1A4A38; }
        
        .message { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        .order-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #9AB0A6; }
        .order-total { margin-top: 20px; padding-top: 15px; border-top: 2px solid #2D674E; }
        .total-line { display: flex; justify-content: space-between; margin: 10px 0; }
        .grand-total { font-size: 1.3em; font-weight: bold; color: #2D674E; }
        
        .free-delivery-msg { background: #2D674E; color: white; padding: 10px; border-radius: 5px; text-align: center; margin-top: 15px; }
        .delivery-notice { background: #F2EADF; padding: 10px; border-radius: 5px; text-align: center; margin-top: 15px; font-size: 14px; border: 1px solid #9AB0A6; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; text-align: center; padding: 30px; margin-top: 50px; }
        .footer-features { display: flex; justify-content: center; gap: 40px; margin-bottom: 20px; flex-wrap: wrap; }
        .feature { text-align: center; }
        
        @media (max-width: 768px) {
            .container { flex-direction: column; }
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
            <h1>PASTIMES</h1>
            <p>Pre-loved branded clothing</p>
        </div>
    </header>
    
    <nav>
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="sell.php">Sell</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="cart.php">Cart</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="messages.php">Messages</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">
        <?php if ($order_success): ?>
            <div style="flex: 1; background: #FEFEFE; padding: 40px; border-radius: 10px; text-align: center;">
                <div style="font-size: 60px;">OK</div>
                <h2 style="color: #2D674E;">Order Placed Successfully!</h2>
                <p style="margin: 20px 0;">Thank you for shopping at Pastimes!</p>
                <p style="margin: 10px 0;">Your order has been received and will be processed within 24 hours.</p>
                <p style="margin: 10px 0;">You can track your order in your dashboard.</p>
                <a href="index.php" style="display: inline-block; margin-top: 30px; background: #2D674E; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="checkout-form">
                <h2>Delivery Details</h2>
                
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="checkout.php">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="fullname" required value="<?php echo $_SESSION['fullname'] ?? ''; ?>">
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label>Email Address *</label>
                            <input type="email" name="email" required value="<?php echo $_SESSION['email'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone" required placeholder="067 876 7564">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Street Address *</label>
                        <input type="text" name="address" required placeholder="House number and street name">
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label>City / Town *</label>
                            <input type="text" name="city" required placeholder="e.g., Kempton Park">
                        </div>
                        <div class="form-group">
                            <label>Postal Code *</label>
                            <input type="text" name="postal_code" required placeholder="e.g., 1619">
                        </div>
                    </div>
                    
                    <div class="payment-methods">
                        <h3>Payment Method</h3>
                        
                        <div class="payment-option" onclick="selectPayment('credit_card')">
                            <input type="radio" name="payment_method" value="Credit Card" id="credit_card" required>
                            <label for="credit_card">Credit / Debit Card</label>
                        </div>
                        
                        <div class="payment-option" onclick="selectPayment('paypal')">
                            <input type="radio" name="payment_method" value="PayPal" id="paypal">
                            <label for="paypal">PayPal</label>
                        </div>
                        
                        <div class="payment-option" onclick="selectPayment('eft')">
                            <input type="radio" name="payment_method" value="EFT / Bank Transfer" id="eft">
                            <label for="eft">EFT / Bank Transfer</label>
                        </div>
                        
                        <div class="payment-option" onclick="selectPayment('cash')">
                            <input type="radio" name="payment_method" value="Cash on Delivery" id="cash">
                            <label for="cash">Cash on Delivery</label>
                        </div>
                    </div>
                    
                    <button type="submit">Place Order</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h3>Order Summary</h3>
                <div id="cart-items">
                    <?php foreach ($_SESSION['cart'] as $item): 
                        $qty = isset($item['quantity']) ? $item['quantity'] : 1;
                        $item_total = $item['price'] * $qty;
                    ?>
                        <div class="order-item">
                            <span><?php echo $item['name']; ?> (<?php echo $item['brand']; ?>) x<?php echo $qty; ?></span>
                            <span>R<?php echo number_format($item_total, 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <div class="total-line">
                        <span>Subtotal:</span>
                        <span>R<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="total-line">
                        <span>Delivery Fee:</span>
                        <span><?php echo ($delivery_fee == 0) ? 'FREE' : 'R' . number_format($delivery_fee, 2); ?></span>
                    </div>
                    <div class="total-line grand-total">
                        <span>Grand Total:</span>
                        <span>R<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                </div>
                
                <?php if ($delivery_fee == 0): ?>
                    <div class="free-delivery-msg">You've qualified for FREE delivery!</div>
                <?php else: ?>
                    <div class="delivery-notice">Add R<?php echo number_format(500 - $subtotal, 2); ?> more for FREE delivery</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
    
    <script>
        function selectPayment(method) {
            document.getElementById(method).checked = true;
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
        }
    </script>
</body>
</html>