<?php
session_start();
require_once '../db/config.php';
require_once '../includes/cart_functions.php';

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$productId) {
    set_message('Invalid product.');
    go_to('cart.php');
}

$stmt = $conn->prepare('SELECT name, stock FROM products WHERE product_id = ?');
$stmt->bind_param('i', $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product || (int) $product['stock'] < 1) {
    set_message('Product is not available.');
    go_to('cart.php');
}

$cart = get_cart();
$currentQuantity = $cart[$productId] ?? 0;
$newQuantity = min($currentQuantity + 1, (int) $product['stock']);
set_cart_quantity($productId, $newQuantity);
set_message($product['name'] . ' added to cart.');

go_to('cart.php');
