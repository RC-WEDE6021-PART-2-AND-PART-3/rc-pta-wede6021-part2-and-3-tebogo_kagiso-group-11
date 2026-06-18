<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'DBConn.php';

// Add product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $image_path = '';
    
    // Handle image upload - using images/ folder
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_path = $target_dir . time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }
    
    $sql = "INSERT INTO tblclothes (name, brand, price, category, size, condition_status, image_path) 
            VALUES ('$name', '$brand', '$price', '$category', '$size', '$condition', '$image_path')";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin_products.php?msg=Product added successfully");
    } else {
        header("Location: admin_products.php?error=" . mysqli_error($conn));
    }
    exit();
}

// Delete product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Get image path to delete file
    $result = mysqli_query($conn, "SELECT image_path FROM tblclothes WHERE clothes_id=$id");
    $row = mysqli_fetch_assoc($result);
    if ($row && $row['image_path'] && file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }
    mysqli_query($conn, "DELETE FROM tblclothes WHERE clothes_id=$id");
    header("Location: admin_products.php?msg=Product deleted");
    exit();
}

// Update product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = intval($_POST['product_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    
    $image_sql = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_path = $target_dir . time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image_sql = ", image_path='$image_path'";
    }
    
    $sql = "UPDATE tblclothes SET 
            name='$name', brand='$brand', price='$price', 
            category='$category', size='$size', condition_status='$condition' 
            $image_sql 
            WHERE clothes_id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin_products.php?msg=Product updated");
    } else {
        header("Location: admin_products.php?error=" . mysqli_error($conn));
    }
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM tblclothes ORDER BY clothes_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Products - Pastimes</title>
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
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .add-form { background: #F2EADF; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 2px solid #9AB0A6; border-radius: 5px; }
        .form-group label { font-weight: bold; display: block; margin-bottom: 5px; }
        .btn-add { background: #2D674E; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-add:hover { background: #1A4A38; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #9AB0A6; }
        th { background: #2D674E; color: white; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .edit-btn { background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; cursor: pointer; }
        footer { margin-top: 30px; text-align: center; color: #6B887C; }
        .modal { display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#FEFEFE; padding:25px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.3); z-index:1000; width:500px; max-height:90vh; overflow-y:auto; }
        .modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; }
        .modal input, .modal select { width:100%; padding:10px; margin:5px 0 15px 0; border:2px solid #9AB0A6; border-radius:5px; }
        .modal button { padding:10px 20px; border:none; border-radius:5px; cursor:pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Manage Products</h2>
            <div>
                <a href="index.php" class="back-btn"><- Back to Website</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="admin-nav">
            <a href="admin_dashboard.php">Users</a>
            <a href="admin_products.php" class="active">Products</a>
            <a href="admin_requests.php">Seller Requests</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_messages.php">Messages</a>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="message"> <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="error"> <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <div class="add-form">
            <h3>Add New Product</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" required>
                    </div>
                    <div class="form-group">
                        <label>Price (R)</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" required>
                            <option>Men</option><option>Women</option>
                            <option>Kids</option><option>Shoes</option>
                            <option>Accessories</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Size</label>
                        <input type="text" name="size" placeholder="S, M, L, XL, 42">
                    </div>
                    <div class="form-group">
                        <label>Condition</label>
                        <select name="condition" required>
                            <option>Like New</option><option>Excellent</option>
                            <option>Good</option><option>Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                </div>
                <button type="submit" name="add_product" class="btn-add" style="margin-top:15px;">Add Product</button>
            </form>
        </div>
        
        <h3>Product List</h3>
        <table>
            <thead>
                <tr><th>Image</th><th>Name</th><th>Brand</th><th>Price</th><th>Category</th><th>Size</th><th>Condition</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td>
                    <?php if ($row['image_path'] && file_exists($row['image_path'])): ?>
                        <img src="<?php echo $row['image_path']; ?>" class="product-img">
                    <?php else: ?>
                        <img src="https://placehold.co/60x60?text=No+Image" class="product-img">
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['brand']); ?></td>
                <td>R<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['size']); ?></td>
                <td><?php echo htmlspecialchars($row['condition_status']); ?></td>
                <td>
                    <a href="#" onclick="editProduct(<?php echo $row['clothes_id']; ?>)" class="edit-btn">Edit</a>
                    <a href="?delete=<?php echo $row['clothes_id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <footer>2024 Pastimes Admin Panel</footer>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal-overlay" id="editOverlay" onclick="closeEdit()"></div>
    <div class="modal" id="editModal">
        <h3 style="color:#2D674E;">Edit Product</h3>
        <form method="POST" enctype="multipart/form-data" id="editForm">
            <input type="hidden" name="product_id" id="edit_id">
            <label>Product Name</label>
            <input type="text" name="name" id="edit_name" required>
            <label>Brand</label>
            <input type="text" name="brand" id="edit_brand" required>
            <label>Price (R)</label>
            <input type="number" step="0.01" name="price" id="edit_price" required>
            <label>Category</label>
            <select name="category" id="edit_category">
                <option>Men</option><option>Women</option>
                <option>Kids</option><option>Shoes</option>
                <option>Accessories</option>
            </select>
            <label>Size</label>
            <input type="text" name="size" id="edit_size">
            <label>Condition</label>
            <select name="condition" id="edit_condition">
                <option>Like New</option><option>Excellent</option>
                <option>Good</option><option>Fair</option>
            </select>
            <label>New Image (optional)</label>
            <input type="file" name="image" accept="image/*">
            <div style="margin-top:15px;">
                <button type="submit" name="update_product" style="background:#2D674E; color:white;">Update Product</button>
                <button type="button" onclick="closeEdit()" style="background:gray; color:white; margin-left:10px;">Cancel</button>
            </div>
        </form>
    </div>
    
    <script>
        function editProduct(id) {
            fetch('get_product.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.clothes_id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_brand').value = data.brand;
                    document.getElementById('edit_price').value = data.price;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_size').value = data.size || '';
                    document.getElementById('edit_condition').value = data.condition_status;
                    document.getElementById('editModal').style.display = 'block';
                    document.getElementById('editOverlay').style.display = 'block';
                });
        }
        function closeEdit() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editOverlay').style.display = 'none';
        }
    </script>
</body>
</html>