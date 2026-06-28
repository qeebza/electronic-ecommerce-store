<?php
session_start();
require_once '../db/config.php';
require_once '../includes/cart_functions.php';

$userId = require_login('../auth/login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    go_to('checkout.php');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$paymentMethod = trim($_POST['payment_method'] ?? '');

$_SESSION['checkout_old'] = [
    'name' => $name,
    'email' => $email,
    'address' => $address,
    'payment_method' => $paymentMethod,
];

$errors = [];
if (strlen($name) < 2 || strlen($name) > 100) {
    $errors[] = 'Enter a valid full name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}
if ($address === '' || strlen($address) > 500) {
    $errors[] = 'Enter a shipping address.';
}

$allowedPaymentMethods = ['card', 'online_banking', 'ewallet'];
if (!in_array($paymentMethod, $allowedPaymentMethods, true)) {
    $errors[] = 'Select a valid payment method.';
}

$items = get_cart_items($pdo);
if (!$items) {
    $errors[] = 'Your cart is empty.';
}

if ($errors) {
    $_SESSION['checkout_errors'] = $errors;
    go_to('checkout.php');
}

$total = calculate_total($items);
$transactionReference = 'PAY-' . date('YmdHis') . '-' . random_int(1000, 9999);
$orderStatus = 'Processing';
$paymentStatus = 'Paid';

try {
    $pdo->beginTransaction();

    foreach ($items as $item) {
        $productId = (int) $item['product_id'];
        $quantity = (int) $item['quantity'];
        $checkStockStmt = $pdo->prepare(
            'SELECT stock FROM products WHERE product_id = ? FOR UPDATE'
        );
        $checkStockStmt->execute([$productId]);
        $product = $checkStockStmt->fetch();

        if (!$product || (int) $product['stock'] < $quantity) {
            throw new RuntimeException($item['name'] . ' does not have enough stock.');
        }
    }

    $orderStmt = $pdo->prepare(
        'INSERT INTO orders
         (user_id, customer_name, email, shipping_address, total_amount, status,
          payment_method, payment_status, transaction_reference)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $orderStmt->execute([
        $userId,
        $name,
        $email,
        $address,
        $total,
        $orderStatus,
        $paymentMethod,
        $paymentStatus,
        $transactionReference,
    ]);
    $orderId = (int) $pdo->lastInsertId();

    $itemStmt = $pdo->prepare(
        'INSERT INTO order_items
         (order_id, product_id, product_name, quantity, price)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stockStmt = $pdo->prepare(
        'UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?'
    );

    foreach ($items as $item) {
        $productId = (int) $item['product_id'];
        $productName = $item['name'];
        $quantity = (int) $item['quantity'];
        $price = (float) $item['price'];

        $itemStmt->execute([$orderId, $productId, $productName, $quantity, $price]);

        $stockStmt->execute([$quantity, $productId, $quantity]);

        if ($stockStmt->rowCount() !== 1) {
            throw new RuntimeException('Stock changed during checkout. Please try again.');
        }
    }

    $pdo->commit();
    clear_cart($pdo);
    $_SESSION['order_message'] = 'Order placed and payment completed successfully.';
    unset($_SESSION['checkout_old']);

    go_to('../order/history.php');
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['checkout_errors'] = [$exception->getMessage()];
    go_to('checkout.php');
}
