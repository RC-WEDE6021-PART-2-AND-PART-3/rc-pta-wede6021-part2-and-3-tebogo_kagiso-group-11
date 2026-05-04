<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Pastimes</title>
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
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; display: flex; gap: 30px; flex-wrap: wrap; }
        .sidebar { width: 250px; background: #FEFEFE; padding: 20px; border-radius: 10px; height: fit-content; }
        .sidebar h3 { color: #2D674E; margin-bottom: 15px; }
        .filter-group { margin-bottom: 20px; }
        .filter-group label { display: block; margin: 8px 0; color: #1A1B1B; cursor: pointer; }
        .products-section { flex: 1; }
        .products-header { margin-bottom: 20px; }
        .products-header h2 { color: #2D674E; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { background: #FEFEFE; border-radius: 10px; padding: 20px; text-align: center; border: 1px solid #9AB0A6; transition: transform 0.3s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-card .product-img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; background: #F2EADF; }
        .product-card .brand { color: #2D674E; font-weight: bold; font-size: 18px; }
        .product-card .name { color: #1A1B1B; margin: 10px 0; }
        .product-card .price { color: #2D674E; font-size: 24px; font-weight: bold; margin: 10px 0; }
        .btn-cart { background: #2D674E; color: #FEFEFE; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; }
        .btn-cart:hover { background: #1A4A38; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; padding: 40px 20px 20px 20px; margin-top: 50px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-section h4 { color: #FEFEFE; margin-bottom: 15px; font-size: 18px; }
        .footer-section p { margin: 8px 0; font-size: 14px; line-height: 1.5; }
        .footer-section a { color: #9AB0A6; text-decoration: none; display: block; margin: 8px 0; font-size: 14px; }
        .footer-section a:hover { color: #FEFEFE; }
        .footer-bottom { text-align: center; padding-top: 20px; margin-top: 20px; border-top: 1px solid #2D674E; font-size: 12px; }
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
        <?php if(isset($_SESSION['username'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">
        <div class="sidebar">
            <h3>Filter</h3>
            <div class="filter-group">
                <h4>Brand</h4>
                <label><input type="checkbox" class="filter-brand" value="ELLESSE" onclick="filterProducts()"> ELLESSE</label>
                <label><input type="checkbox" class="filter-brand" value="REDBAT" onclick="filterProducts()"> REDBAT</label>
                <label><input type="checkbox" class="filter-brand" value="ADIDAS" onclick="filterProducts()"> ADIDAS</label>
                <label><input type="checkbox" class="filter-brand" value="VANS" onclick="filterProducts()"> VANS</label>
            </div>
        </div>
        
        <div class="products-section">
            <div class="products-header">
                <h2>All Products</h2>
                <p>Browse our collection of pre-loved branded clothing</p>
            </div>
            <div class="products-grid" id="productsGrid"></div>
        </div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Pastimes</h4>
                <p>Kempton Park's premier marketplace for pre-loved branded clothing.</p>
                <p><strong>Location:</strong> Kempton Park, SA</p>
                <p><strong>Email:</strong> kayge_tebogo.pastime@gmail.com</p>
                <p><strong>Phone:</strong> 067 876 7564 / 075 675 6543</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="sell.php">Sell an Item</a>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact Us</a>
            </div>
            <div class="footer-section">
                <h4>Customer Service</h4>
                <a href="#">Track Order</a>
                <a href="#">Shipping Info</a>
                <a href="#">Returns Policy</a>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">WhatsApp</a>
                <p style="margin-top: 10px;"><strong>Payment:</strong> Visa | Mastercard | EFT</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p>
            <p>Based in Kempton Park | Serving South Africa</p>
        </div>
    </footer>
    
    <script>
        const products = [
            { id: 1, name: "Vintage Logo Tee", brand: "ELLESSE", price: 250, imgFile: "ellesse.jpg" },
            { id: 2, name: "Obsessed To Progress Tee", brand: "REDBAT", price: 180, imgFile: "redbat.jpg" },
            { id: 3, name: "Originals Trefoil Tee", brand: "ADIDAS", price: 350, imgFile: "adidas.jpg" },
            { id: 4, name: "Old Skool", brand: "VANS", price: 420, imgFile: "vans.jpg" }
        ];
        
        function displayProducts(productsToShow) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';
            productsToShow.forEach(p => {
                grid.innerHTML += `
                    <div class="product-card">
                        <img src="images/${p.imgFile}" alt="${p.name}" class="product-img" onerror="this.src='https://placehold.co/250x180?text=Pastimes'">
                        <div class="brand">${p.brand}</div>
                        <div class="name">${p.name}</div>
                        <div class="price">R${p.price}</div>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="${p.id}">
                            <input type="hidden" name="product_name" value="${p.name}">
                            <input type="hidden" name="product_brand" value="${p.brand}">
                            <input type="hidden" name="product_price" value="${p.price}">
                            <button type="submit" class="btn-cart">Add to Cart</button>
                        </form>
                    </div>
                `;
            });
        }
        
        function filterProducts() {
            let selectedBrands = Array.from(document.querySelectorAll('.filter-brand:checked')).map(cb => cb.value);
            let filtered = products;
            if (selectedBrands.length > 0) {
                filtered = products.filter(p => selectedBrands.includes(p.brand));
            }
            displayProducts(filtered);
        }
        
        displayProducts(products);
    </script>
</body>
</html>