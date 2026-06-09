<?php
session_start();
$product_id = $_GET['id'] ?? 0;

// Placeholder cart logic. Use session or database later.
$_SESSION['cart'][] = $product_id;

header('Location: cart.php');
exit;
?>
