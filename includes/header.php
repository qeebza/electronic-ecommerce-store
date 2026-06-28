<script src="/electronic-ecommerce-store/assets/js/confirmed.js"></script>

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
    <link rel="stylesheet" href="/electronic-ecommerce-store/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
</head>
<body>
<header>
    <div class="header-brand">
        <a class="logo" href="/electronic-ecommerce-store/index.php">Electronic Store</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="nav-user">Hi, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Customer'); ?></span>
        <?php endif; ?>
    </div>

    <nav>
        <a href="/electronic-ecommerce-store/index.php">Home</a>
        <a href="/electronic-ecommerce-store/products/list.php">Products</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/electronic-ecommerce-store/cart/cart.php">Cart</a>
            <a href="/electronic-ecommerce-store/customer/profile.php">Profile</a>
        <?php else: ?>
            <a href="/electronic-ecommerce-store/cart/cart.php">Cart</a>
            <a href="/electronic-ecommerce-store/auth/login.php">Profile</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/electronic-ecommerce-store/admin/dashboard.php">Admin</a>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/electronic-ecommerce-store/auth/login.php">Login</a>
        <?php else: ?>
            <a href="/electronic-ecommerce-store/auth/logout.php" onclick="return confirmLogout()">Logout</a>
        <?php endif; ?>
    </nav>
</header>
