<?php
include '../includes/header.php';
include '../db/config.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /electronic-ecommerce-store/auth/login.php');
    exit;
}

// Total Sales
$res = mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM orders WHERE status != 'Cancelled'");
$row = mysqli_fetch_assoc($res);
$total_sales = $row['total'] ? number_format($row['total'], 2) : '0.00';

// Total Orders
$res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders");
$total_orders = mysqli_fetch_assoc($res)['cnt'];

// Pending Orders
$res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders WHERE status = 'Pending'");
$pending_orders = mysqli_fetch_assoc($res)['cnt'];

// Low Stock (stock < 5)
$res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM products WHERE stock < 5");
$low_stock = mysqli_fetch_assoc($res)['cnt'];
?>
<main class="container">
    <h1>Admin Dashboard</h1>
    <div class="grid">
        <div class="card"><h3>Total Sales</h3><p>RM <?php echo $total_sales; ?></p></div>
        <div class="card"><h3>Total Orders</h3><p><?php echo $total_orders; ?></p></div>
        <div class="card"><h3>Pending Orders</h3><p><?php echo $pending_orders; ?></p></div>
        <div class="card"><h3>Low Stock Items</h3><p><?php echo $low_stock; ?></p></div>
    </div>
    <a class="btn" href="products.php">Manage Products</a>
    <a class="btn" href="orders.php">Manage Orders</a>
</main>
<?php include '../includes/footer.php'; ?>
