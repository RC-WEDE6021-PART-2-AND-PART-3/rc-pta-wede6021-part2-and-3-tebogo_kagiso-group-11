<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clothingstore";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Create tables if they don't exist
$sql = "CREATE TABLE IF NOT EXISTS tblmessages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT,
    sender_username VARCHAR(50),
    receiver_username VARCHAR(50),
    subject VARCHAR(100),
    message TEXT,
    is_read INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS tblseller_requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    username VARCHAR(50),
    product_name VARCHAR(100),
    brand VARCHAR(50),
    price DECIMAL(10,2),
    category VARCHAR(50),
    size VARCHAR(10),
    item_condition VARCHAR(20),
    description TEXT,
    image_path VARCHAR(255),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE IF NOT EXISTS tblorders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    username VARCHAR(50),
    fullname VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    postal_code VARCHAR(10),
    payment_method VARCHAR(50),
    items TEXT,
    subtotal DECIMAL(10,2),
    delivery_fee DECIMAL(10,2),
    grand_total DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);

// Add image column to tblclothes if not exists
$check = mysqli_query($conn, "SHOW COLUMNS FROM tblclothes LIKE 'image_path'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE tblclothes ADD COLUMN image_path VARCHAR(255) DEFAULT NULL");
}
?>