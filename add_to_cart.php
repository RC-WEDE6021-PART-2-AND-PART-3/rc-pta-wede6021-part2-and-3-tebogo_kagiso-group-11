<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $_POST['product_name'],
        'brand' => $_POST['product_brand'] ?? '',
        'price' => $_POST['product_price']
    ];
}

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'shop.php'));
exit();
?>