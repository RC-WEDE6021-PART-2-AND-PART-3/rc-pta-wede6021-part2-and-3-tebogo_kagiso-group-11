<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}

// Handle update quantity
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        $_SESSION['cart'][$id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
    $subtotal += $item['price'] * $quantity;
}

$delivery_fee = ($subtotal >= 500) ? 0 : 50;
$total = $subtotal + $delivery_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Pastimes</title>
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
        
        .cart-section { flex: 2; background: #FEFEFE; border-radius: 10px; padding: 30px; }
        .summary-section { flex: 1.2; background: #F2EADF; border-radius: 10px; padding: 30px; height: fit-content; position: sticky; top: 20px; }
        
        h2 { color: #2D674E; margin-bottom: 20px; border-bottom: 2px solid #2D674E; padding-bottom: 10px; display: inline-block; }
        h3 { color: #2D674E; margin-bottom: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #9AB0A6; }
        th { background: #F2EADF; font-weight: bold; color: #1A1B1B; }
        
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; background: #F2EADF; }
        .quantity-input { width: 60px; padding: 5px; border: 2px solid #9AB0A6; border-radius: 5px; text-align: center; }
        .update-btn { background: #2D674E; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px; }
        .update-btn:hover { background: #1A4A38; }
        .remove-btn { background: #dc3545; color: white; padding: 5px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; display: inline-block; }
        .remove-btn:hover { background: #c82333; }
        
        .total-line { display: flex; justify-content: space-between; margin: 15px 0; }
        .grand-total { font-size: 1.3em; font-weight: bold; color: #2D674E; border-top: 2px solid #2D674E; padding-top: 15px; margin-top: 15px; }
        
        .checkout-btn { background: #2D674E; color: #FEFEFE; border: none; padding: 15px; border-radius: 5px; cursor: pointer; font-size: 18px; font-weight: bold; width: 100%; margin-top: 20px; transition: background 0.3s; }
        .checkout-btn:hover { background: #1A4A38; }
        
        .continue-shopping { display: block; text-align: center; margin-top: 20px; color: #2D674E; text-decoration: none; font-weight: bold; }
        .empty-cart { text-align: center; padding: 50px; color: #6B887C; }
        .empty-cart a { color: #2D674E; text-decoration: none; font-weight: bold; }
        
        .free-delivery-msg { background: #2D674E; color: white; padding: 10px; border-radius: 5px; text-align: center; margin-top: 15px; }
        .delivery-notice { background: #F2EADF; padding: 10px; border-radius: 5px; text-align: center; margin-top: 15px; font-size: 14px; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; text-align: center; padding: 40px 20px 20px 20px; margin-top: 50px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-section h4 { color: #FEFEFE; margin-bottom: 15px; font-size: 18px; }
        .footer-section p { margin: 8px 0; font-size: 14px; line-height: 1.5; }
        .footer-section a { color: #9AB0A6; text-decoration: none; display: block; margin: 8px 0; font-size: 14px; }
        .footer-section a:hover { color: #FEFEFE; }
        .footer-bottom { text-align: center; padding-top: 20px; margin-top: 20px; border-top: 1px solid #2D674E; font-size: 12px; }
        
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            th, td { padding: 8px; font-size: 12px; }
            .product-img { width: 40px; height: 40px; }
        }
    </style>
</head>
<body>
    <div class="top-bar">Free delivery on orders over R500</div>
    <div class="help-bar">
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="#">Track Order</a>
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
        <a href="cart.php">Cart</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">
        <div class="cart-section">
            <h2>Shopping Cart</h2>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <p>Your cart is empty</p>
                    <a href="shop.php">Continue Shopping</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item): 
                            $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                            $item_total = $item['price'] * $quantity;
                        ?>
                        <tr>
                            <td>
                                <img src="images/<?php echo strtolower(str_replace(' ', '_', $item['brand'])) . '.jpg'; ?>" 
                                     alt="<?php echo $item['name']; ?>" 
                                     class="product-img"
                                     onerror="this.src='https://placehold.co/60x60?text=Pastimes'">
                                <?php echo $item['name']; ?>
                            </td>
                            <td><?php echo $item['brand']; ?></td>
                            <td>R<?php echo $item['price']; ?></td>
                            <td>
                                <form method="POST" action="cart.php" style="display: flex; gap: 5px; align-items: center;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" max="10" class="quantity-input">
                                    <button type="submit" name="update_quantity" class="update-btn">Update</button>
                                </form>
                            </td>
                            <td>R<?php echo $item_total; ?></td>
                            <td><a href="cart.php?remove=<?php echo $item['id']; ?>" class="remove-btn" onclick="return confirm('Remove this item?')">Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="summary-section">
            <h3>Order Summary</h3>
            
            <div class="total-line">
                <span>Subtotal:</span>
                <span>R<?php echo number_format($subtotal, 2); ?></span>
            </div>
            
            <div class="total-line">
                <span>Delivery Fee:</span>
                <span><?php echo ($delivery_fee == 0) ? 'FREE' : 'R' . number_format($delivery_fee, 2); ?></span>
            </div>
            
            <div class="total-line grand-total">
                <span>Total:</span>
                <span>R<?php echo number_format($total, 2); ?></span>
            </div>
            
            <?php if ($delivery_fee == 0): ?>
                <div class="free-delivery-msg">
                    🎉 You've qualified for FREE delivery!
                </div>
            <?php else: ?>
                <div class="delivery-notice">
                    Add R<?php echo number_format(500 - $subtotal, 2); ?> more for FREE delivery
                </div>
            <?php endif; ?>
            
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="POST" action="checkout.php">
                    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                </form>
            <?php endif; ?>
            
            <a href="shop.php" class="continue-shopping">← Continue Shopping</a>
        </div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Pastimes</h4>
                <p>Kempton Park's premier marketplace for pre-loved branded clothing.</p>
                <p><strong>Email:</strong> kayge_tebogo.pastime@gmail.com</p>
                <p><strong>Phone:</strong> 067 876 7564 / 075 675 6543</p>
                <p><strong>Location:</strong> Kempton Park, South Africa</p>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="sell.php">Sell an Item</a>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact Us</a>
                <a href="cart.php">Cart</a>
            </div>
            
            <div class="footer-section">
                <h4>Customer Service</h4>
                <a href="#">Track Order</a>
                <a href="#">Shipping Info</a>
                <a href="#">Returns Policy</a>
                <a href="#">Terms & Conditions</a>
                <a href="#">Privacy Policy</a>
            </div>
            
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>📞 067 876 7564</p>
                <p>📞 075 675 6543</p>
                <p>📧 kayge_tebogo.pastime@gmail.com</p>
                <p>📍 Kempton Park, Gauteng</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p>
            <p>Based in Kempton Park | Serving South Africa | All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>