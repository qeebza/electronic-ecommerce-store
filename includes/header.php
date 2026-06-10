<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Store</title>
    <link rel="stylesheet" href="/electronic-ecommerce-store/assets/css/style.css">
</head>
<body>
<header>
    <div class="logo">Electronic Store</div>
    <nav>
        <a href="/electronic-ecommerce-store/index.php">Home</a>
        <a href="/electronic-ecommerce-store/products/list.php">Products</a>
        <a href="/electronic-ecommerce-store/cart/cart.php">Cart</a>
        <a href="/electronic-ecommerce-store/customer/profile.php">Profile</a>
        <a href="/electronic-ecommerce-store/admin/dashboard.php">Admin</a>
        <a href="/electronic-ecommerce-store/auth/login.php">Login</a>
    </nav>
</header>
