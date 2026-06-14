<?php
session_start();
require_once '../db/config.php';
require_once '../includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    go_to('cart.php');
}

$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

if (!$productId || $quantity === false || $quantity === null || $quantity < 0) {
    set_message('Invalid cart update.');
    go_to('cart.php');
}

if ($quantity === 0) {
    set_cart_quantity($productId, 0);
    set_message('Item removed from cart.');
    go_to('cart.php');
}

$stmt = $pdo->prepare('SELECT stock FROM products WHERE product_id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    set_cart_quantity($productId, 0);
    set_message('Product no longer exists.');
} else {
    set_cart_quantity($productId, min($quantity, (int) $product['stock']));
    set_message('Cart updated.');
}

go_to('cart.php');
