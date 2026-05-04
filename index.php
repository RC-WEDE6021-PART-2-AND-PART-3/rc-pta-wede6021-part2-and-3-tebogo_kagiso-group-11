<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes - Pre-loved Branded Clothing</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #FEFEFE; }
        .top-bar { background-color: #2D674E; color: #FEFEFE; padding: 8px; text-align: center; font-size: 14px; }
        .help-bar { background-color: #F2EADF; padding: 8px 20px; text-align: right; font-size: 12px; }
        .help-bar a { color: #2D674E; text-decoration: none; margin-left: 20px; }
        .help-bar .admin-link { background: #2D674E; color: #FEFEFE; padding: 5px 12px; border-radius: 20px; margin-left: 15px; }
        .help-bar .admin-link:hover { background: #1A4A38; }
        header { background-color: #FEFEFE; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; border-bottom: 1px solid #9AB0A6; }
        .logo h1 { color: #2D674E; font-size: 28px; }
        .logo p { color: #6B887C; font-size: 12px; }
        .search-bar input { padding: 10px; width: 300px; border: 2px solid #9AB0A6; border-radius: 25px; outline: none; }
        .search-bar input:focus { border-color: #2D674E; }
        nav { background-color: #2D674E; padding: 15px; text-align: center; }
        nav a { color: #FEFEFE; text-decoration: none; margin: 0 15px; padding: 8px 16px; border-radius: 5px; transition: background 0.3s; }
        nav a:hover { background-color: #1A4A38; }
        .hero { background: linear-gradient(135deg, #2D674E 0%, #1A4A38 100%); color: #FEFEFE; text-align: center; padding: 60px 20px; }
        .hero h2 { font-size: 2.5em; margin-bottom: 15px; }
        .hero p { font-size: 1.2em; margin-bottom: 25px; }
        .btn-primary { background-color: #FEFEFE; color: #2D674E; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; display: inline-block; margin: 0 10px; }
        .btn-secondary { background-color: transparent; color: #FEFEFE; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; border: 2px solid #FEFEFE; display: inline-block; margin: 0 10px; }
        .welcome-msg { background-color: #2D674E; color: #FEFEFE; padding: 10px; text-align: center; }
        .categories { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        .categories h3 { text-align: center; color: #2D674E; font-size: 28px; margin-bottom: 15px; }
        .categories p { text-align: center; color: #6B887C; margin-bottom: 30px; }
        .category-grid { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        .category-card { background: #F2EADF; padding: 30px; border-radius: 10px; text-align: center; width: 150px; cursor: pointer; transition: transform 0.3s; }
        .category-card:hover { transform: translateY(-5px); background: #2D674E; color: #FEFEFE; }
        .category-card .icon { font-size: 40px; display: block; margin-bottom: 10px; }
        .featured { background-color: #F2EADF; padding: 50px 0; }
        .featured h3 { text-align: center; color: #2D674E; font-size: 28px; margin-bottom: 15px; }
        .featured p { text-align: center; color: #6B887C; margin-bottom: 30px; }
        .products-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; padding: 0 20px; }
        .product-card { background: #FEFEFE; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s; border: 1px solid #9AB0A6; }
        .product-card:hover { transform: translateY(-5px); }
        .product-card .product-img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; background: #F2EADF; }
        .product-card .brand { color: #2D674E; font-weight: bold; font-size: 18px; }
        .product-card .name { color: #1A1B1B; margin: 10px 0; }
        .product-card .price { color: #2D674E; font-size: 1.5em; font-weight: bold; }
        .btn-cart { background-color: #2D674E; color: #FEFEFE; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px; width: 100%; transition: background 0.3s; }
        .btn-cart:hover { background-color: #1A4A38; }
        
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
        <a href="admin_login.php" class="admin-link">Admin Login</a>
    </div>
    
    <header>
        <div class="logo">
            <h1>Pastimes</h1>
            <p>Pre-loved branded clothing</p>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search for branded clothing..." id="searchInput">
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
            <a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <?php if(isset($_SESSION['username'])): ?>
        <div class="welcome-msg">Welcome back, <?php echo $_SESSION['fullname']; ?>!</div>
    <?php endif; ?>
    
    <div class="hero">
        <h2>Pre-Loved. Still Loved.</h2>
        <p>Discover premium second-hand branded clothing. Sustainable style at amazing prices.</p>
        <a href="shop.php" class="btn-primary">Shop Now</a>
        <a href="sell.php" class="btn-secondary">Start Selling</a>
    </div>
    
    <div class="categories">
        <h3>Shop by Category</h3>
        <p>Find exactly what you're looking for</p>
        <div class="category-grid">
            <div class="category-card"><span class="icon">👜</span>Accessories</div>
            <div class="category-card"><span class="icon">👨</span>Men</div>
            <div class="category-card"><span class="icon">👩</span>Women</div>
            <div class="category-card"><span class="icon">👶</span>Kids</div>
            <div class="category-card"><span class="icon">👟</span>Shoes</div>
        </div>
    </div>
    
    <div class="featured">
        <h3>Featured Finds</h3>
        <p>Hand-picked premium second-hand pieces</p>
        <div class="products-grid" id="productsGrid"></div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Pastimes</h4>
                <p>Kempton Park's premier marketplace for pre-loved branded clothing. Sustainable style at amazing prices.</p>
                <p><strong>Location:</strong> Kempton Park, South Africa</p>
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
                <a href="#">Terms & Conditions</a>
            </div>
            
            <div class="footer-section">
                <h4>Follow Us</h4>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
                <a href="#">WhatsApp</a>
                <p style="margin-top: 10px;"><strong>Payment:</strong> Visa | Mastercard | EFT</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Pastimes | Tebogo Mabusela (ST10443781) & Kagiso Maputla (ST10455770)</p>
            <p>Based in Kempton Park | Serving South Africa | All Rights Reserved</p>
        </div>
    </footer>
    
    <script>
        const products = [
            { id: 1, name: "Vintage Logo Tee", brand: "ELLESSE", price: 250, category: "Men", imgFile: "ellesse.jpg" },
            { id: 2, name: "Obsessed To Progress Tee", brand: "REDBAT", price: 180, category: "Men", imgFile: "redbat.jpg" },
            { id: 3, name: "Originals Trefoil Tee", brand: "ADIDAS", price: 350, category: "Men", imgFile: "adidas.jpg" },
            { id: 4, name: "Old Skool", brand: "VANS", price: 420, category: "Shoes", imgFile: "vans.jpg" }
        ];
        
        function displayProducts(productsToShow) {
            const grid = document.getElementById('productsGrid');
            if (!grid) return;
            grid.innerHTML = '';
            productsToShow.forEach(product => {
                grid.innerHTML += `
                    <div class="product-card">
                        <img src="images/${product.imgFile}" alt="${product.name}" class="product-img" onerror="this.src='https://placehold.co/250x180?text=Pastimes'">
                        <div class="brand">${product.brand}</div>
                        <div class="name">${product.name}</div>
                        <div class="price">R${product.price}</div>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="${product.id}">
                            <input type="hidden" name="product_name" value="${product.name}">
                            <input type="hidden" name="product_brand" value="${product.brand}">
                            <input type="hidden" name="product_price" value="${product.price}">
                            <button type="submit" class="btn-cart">Add to Cart</button>
                        </form>
                    </div>
                `;
            });
        }
        
        function filterCategory(category) {
            const filtered = products.filter(p => p.category === category);
            displayProducts(filtered);
            document.querySelector('.featured h3').innerHTML = category + " Collection";
        }
        
        document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = products.filter(p => 
                p.name.toLowerCase().includes(searchTerm) || 
                p.brand.toLowerCase().includes(searchTerm)
            );
            displayProducts(filtered);
            document.querySelector('.featured h3').innerHTML = "Search Results";
        });
        
        displayProducts(products);
    </script>
</body>
</html>