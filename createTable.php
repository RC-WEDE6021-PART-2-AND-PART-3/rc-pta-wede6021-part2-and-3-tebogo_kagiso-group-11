<?php
include 'DBConn.php';

// Drop tables if they exist
mysqli_query($conn, "DROP TABLE IF EXISTS tblAorder");
mysqli_query($conn, "DROP TABLE IF EXISTS tblClothes");
mysqli_query($conn, "DROP TABLE IF EXISTS tblUser");
mysqli_query($conn, "DROP TABLE IF EXISTS tblAdmin");

// Create tblAdmin
$sql = "CREATE TABLE tblAdmin (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    fullname VARCHAR(100) NOT NULL
)";
mysqli_query($conn, $sql);
echo "✓ tblAdmin created<br>";

// Insert default admin
$hashedPassword = md5("admin123");
mysqli_query($conn, "INSERT INTO tblAdmin (username, password, email, fullname) VALUES ('admin', '$hashedPassword', 'admin@pastimes.co.za', 'Store Admin')");
echo "✓ Admin user added (username: admin, password: admin123)<br>";

// Create tblUser
$sql = "CREATE TABLE tblUser (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);
echo "✓ tblUser created<br>";

// Load data from userData.txt (5 South African users)
$file = fopen("userData.txt", "r");
if ($file) {
    while (($line = fgets($file)) !== false) {
        $data = explode("|", trim($line));
        if (count($data) == 5) {
            $fullname = $data[0];
            $email = $data[1];
            $username = $data[2];
            $plain_password = $data[3];
            $status = $data[4];
            $hashedPassword = md5($plain_password);
            
            $sql = "INSERT INTO tblUser (fullname, email, username, password, status) 
                    VALUES ('$fullname', '$email', '$username', '$hashedPassword', '$status')";
            mysqli_query($conn, $sql);
        }
    }
    fclose($file);
    echo "✓ 5 South African users loaded from userData.txt<br>";
} else {
    // Default users if file not found
    mysqli_query($conn, "INSERT INTO tblUser (fullname, email, username, password, status) VALUES 
    ('Thabo Nkosi', 'thabo.nkosi@email.com', 'thabo123', '" . md5("password123") . "', 'approved'),
    ('Lerato Molefe', 'lerato.m@email.com', 'lerato_m', '" . md5("password123") . "', 'approved'),
    ('Sipho Dlamini', 'sipho.d@email.com', 'sipho_d', '" . md5("password123") . "', 'pending'),
    ('Nomsa Khumalo', 'nomsa.k@email.com', 'nomsa_k', '" . md5("password123") . "', 'approved'),
    ('Kagiso Maputla', 'kagiso.m@email.com', 'kagiso_m', '" . md5("password123") . "', 'approved')");
    echo "✓ Default 5 South African users inserted<br>";
}

// Create tblClothes
$sql = "CREATE TABLE tblClothes (
    clothes_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50),
    size VARCHAR(10),
    condition_status VARCHAR(20)
)";
mysqli_query($conn, $sql);
echo "✓ tblClothes created<br>";

// Insert sample clothes (4 products)
mysqli_query($conn, "INSERT INTO tblClothes (name, brand, price, category, size, condition_status) VALUES 
('Vintage Logo Tee', 'ELLESSE', 250, 'Men', 'L', 'Like New'),
('Obsessed To Progress Tee', 'REDBAT', 180, 'Men', 'M', 'Good'),
('Originals Trefoil Tee', 'ADIDAS', 350, 'Men', 'XL', 'Excellent'),
('Old Skool', 'VANS', 420, 'Shoes', '42', 'Good')");
echo "✓ 4 sample clothes added<br>";

// Create tblAorder
$sql = "CREATE TABLE tblAorder (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    clothes_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    quantity INT DEFAULT 1,
    total_price DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id),
    FOREIGN KEY (clothes_id) REFERENCES tblClothes(clothes_id)
)";
mysqli_query($conn, $sql);
echo "✓ tblAorder created<br>";

echo "<br><strong>✓ Database setup complete!</strong>";
echo "<br>📋 5 South African users loaded:";
echo "<br>   1. Thabo Nkosi (thabo123) - Approved";
echo "<br>   2. Lerato Molefe (lerato_m) - Approved";
echo "<br>   3. Sipho Dlamini (sipho_d) - Pending";
echo "<br>   4. Nomsa Khumalo (nomsa_k) - Approved";
echo "<br>   5. Kagiso Maputla (kagiso_m) - Approved";
echo "<br><br>🔑 All passwords: password123";
echo "<br>👑 Admin: admin / admin123";
?>