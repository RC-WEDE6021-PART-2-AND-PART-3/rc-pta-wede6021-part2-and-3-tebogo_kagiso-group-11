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
        .filter-group label input { margin-right: 8px; }
        .products-section { flex: 1; }
        .products-header { margin-bottom: 20px; }
        .products-header h2 { color: #2D674E; }
        .products-header p { color: #6B887C; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { background: #FEFEFE; border-radius: 10px; padding: 20px; text-align: center; border: 1px solid #9AB0A6; transition: transform 0.3s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-card .product-img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; background: #F2EADF; }
        .product-card .brand { color: #2D674E; font-weight: bold; font-size: 18px; }
        .product-card .name { color: #1A1B1B; margin: 10px 0; font-size: 16px; }
        .product-card .price { color: #2D674E; font-size: 24px; font-weight: bold; margin: 10px 0; }
        .btn-cart { background: #2D674E; color: #FEFEFE; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 14px; transition: background 0.3s; }
        .btn-cart:hover { background: #1A4A38; }
        .category-filter-btn { background: #F2EADF; border: 2px solid #9AB0A6; padding: 8px 16px; border-radius: 20px; cursor: pointer; margin: 5px; transition: all 0.3s; font-size: 13px; }
        .category-filter-btn:hover, .category-filter-btn.active { background: #2D674E; color: #FEFEFE; border-color: #2D674E; }
        .filter-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .reset-btn { background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 14px; transition: background 0.3s; }
        .reset-btn:hover { background: #c82333; }
        
        footer { background-color: #1A1B1B; color: #9AB0A6; padding: 40px 20px 20px 20px; margin-top: 50px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-section h4 { color: #FEFEFE; margin-bottom: 15px; font-size: 18px; }
        .footer-section p { margin: 8px 0; font-size: 14px; line-height: 1.5; }
        .footer-section a { color: #9AB0A6; text-decoration: none; display: block; margin: 8px 0; font-size: 14px; }
        .footer-section a:hover { color: #FEFEFE; }
        .footer-bottom { text-align: center; padding-top: 20px; margin-top: 20px; border-top: 1px solid #2D674E; font-size: 12px; }
        
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            .sidebar { width: 100%; }
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
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    
    <div class="container">
        <div class="sidebar">
            <h3>Filter by Brand</h3>
            <div class="filter-group">
                <label><input type="checkbox" class="filter-brand" value="ELLESSE" onclick="filterProducts()"> ELLESSE</label>
                <label><input type="checkbox" class="filter-brand" value="REDBAT" onclick="filterProducts()"> REDBAT</label>
                <label><input type="checkbox" class="filter-brand" value="ADIDAS" onclick="filterProducts()"> ADIDAS</label>
                <label><input type="checkbox" class="filter-brand" value="VANS" onclick="filterProducts()"> VANS</label>
                <label><input type="checkbox" class="filter-brand" value="GALXBOY" onclick="filterProducts()"> GALXBOY</label>
                <label><input type="checkbox" class="filter-brand" value="NIKE" onclick="filterProducts()"> NIKE</label>
            </div>
            
            <h3>Filter by Category</h3>
            <div class="filter-actions">
                <button class="category-filter-btn active" onclick="filterByCategory('all', this)">All</button>
                <button class="category-filter-btn" onclick="filterByCategory('Men', this)">Men</button>
                <button class="category-filter-btn" onclick="filterByCategory('Women', this)">Women</button>
                <button class="category-filter-btn" onclick="filterByCategory('Kids', this)">Kids</button>
                <button class="category-filter-btn" onclick="filterByCategory('Shoes', this)">Shoes</button>
                <button class="category-filter-btn" onclick="filterByCategory('Accessories', this)">Accessories</button>
            </div>
            
            <div style="margin-top: 20px;">
                <button onclick="resetFilters()" class="reset-btn">Reset Filters</button>
            </div>
        </div>
        
        <div class="products-section">
            <div class="products-header">
                <h2 id="categoryTitle">All Products</h2>
                <p id="productCount">Browse our collection of pre-loved branded clothing</p>
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
            // Original products
            { id: 1, name: "Vintage Logo Tee", brand: "ELLESSE", price: 250, category: "Men", imgFile: "ellesse.jpg" },
            { id: 2, name: "Obsessed To Progress Tee", brand: "REDBAT", price: 180, category: "Men", imgFile: "redbat.jpg" },
            { id: 3, name: "Originals Trefoil Tee", brand: "ADIDAS", price: 350, category: "Men", imgFile: "adidas.jpg" },
            { id: 4, name: "Old Skool", brand: "VANS", price: 420, category: "Shoes", imgFile: "vans.jpg" },
            
            // New products with correct image file names (with spaces and .jpeg)
            { id: 5, name: "Black Hoodie", brand: "GALXBOY", price: 450, category: "Men", imgFile: "black hoodie.jpeg" },
            { id: 6, name: "Galxboy Tee", brand: "GALXBOY", price: 280, category: "Men", imgFile: "galxboy tee.jpg.jpeg" },
            { id: 7, name: "Nike Pants", brand: "NIKE", price: 380, category: "Men", imgFile: "nike pants.jpeg" },
            { id: 8, name: "Nike Tee", brand: "NIKE", price: 320, category: "Men", imgFile: "nike tee.jpeg" },
            { id: 9, name: "Redbat Tee", brand: "REDBAT", price: 200, category: "Men", imgFile: "redbat tee.jpeg" }
        ];
        
        let currentCategory = 'all';
        
        function displayProducts(productsToShow) {
            const grid = document.getElementById('productsGrid');
            const count = document.getElementById('productCount');
            grid.innerHTML = '';
            
            if (productsToShow.length === 0) {
                grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 50px; color: #6B887C;">No products found matching your criteria.</p>';
                count.textContent = 'No products found';
                return;
            }
            
            count.textContent = productsToShow.length + ' products found';
            
            productsToShow.forEach(p => {
                // URL encode the image filename to handle spaces
                const imgSrc = 'images/' + encodeURIComponent(p.imgFile);
                grid.innerHTML += `
                    <div class="product-card">
                        <img src="${imgSrc}" alt="${p.name}" class="product-img" onerror="this.src='https://placehold.co/250x180?text=Pastimes'">
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
                filtered = filtered.filter(p => selectedBrands.includes(p.brand));
            }
            
            if (currentCategory !== 'all') {
                filtered = filtered.filter(p => p.category === currentCategory);
            }
            
            displayProducts(filtered);
        }
        
        function filterByCategory(category, btn) {
            currentCategory = category;
            
            document.querySelectorAll('.category-filter-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            
            document.getElementById('categoryTitle').textContent = category === 'all' ? 'All Products' : category + ' Collection';
            
            filterProducts();
        }
        
        function resetFilters() {
            document.querySelectorAll('.filter-brand').forEach(cb => cb.checked = false);
            
            currentCategory = 'all';
            document.querySelectorAll('.category-filter-btn').forEach(b => {
                b.classList.remove('active');
                if (b.textContent === 'All') b.classList.add('active');
            });
            document.getElementById('categoryTitle').textContent = 'All Products';
            
            displayProducts(products);
        }
        
        displayProducts(products);
    </script>
</body>
</html>