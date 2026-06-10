<?php
session_start();
require_once '../db/config.php';
require_once '../includes/cart_functions.php';

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
if (strlen($address) < 10 || strlen($address) > 500) {
    $errors[] = 'Enter a complete shipping address.';
}

$allowedPaymentMethods = ['card', 'online_banking', 'ewallet'];
if (!in_array($paymentMethod, $allowedPaymentMethods, true)) {
    $errors[] = 'Select a valid payment method.';
}

$items = get_cart_items($conn);
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

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn->begin_transaction();

    foreach ($items as $item) {
        $productId = (int) $item['product_id'];
        $quantity = (int) $item['quantity'];
        $checkStockStmt = $conn->prepare(
            'SELECT stock FROM products WHERE product_id = ? FOR UPDATE'
        );
        $checkStockStmt->bind_param('i', $productId);
        $checkStockStmt->execute();
        $product = $checkStockStmt->get_result()->fetch_assoc();

        if (!$product || (int) $product['stock'] < $quantity) {
            throw new RuntimeException($item['name'] . ' does not have enough stock.');
        }
    }

    $orderStmt = $conn->prepare(
        'INSERT INTO orders
         (customer_name, email, shipping_address, total_amount, status,
          payment_method, payment_status, transaction_reference)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $orderStmt->bind_param(
        'sssdssss',
        $name,
        $email,
        $address,
        $total,
        $orderStatus,
        $paymentMethod,
        $paymentStatus,
        $transactionReference
    );
    $orderStmt->execute();
    $orderId = $conn->insert_id;

    $itemStmt = $conn->prepare(
        'INSERT INTO order_items
         (order_id, product_id, product_name, quantity, price)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stockStmt = $conn->prepare(
        'UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?'
    );

    foreach ($items as $item) {
        $productId = (int) $item['product_id'];
        $productName = $item['name'];
        $quantity = (int) $item['quantity'];
        $price = (float) $item['price'];

        $itemStmt->bind_param('iisid', $orderId, $productId, $productName, $quantity, $price);
        $itemStmt->execute();

        $stockStmt->bind_param('iii', $quantity, $productId, $quantity);
        $stockStmt->execute();

        if ($stockStmt->affected_rows !== 1) {
            throw new RuntimeException('Stock changed during checkout. Please try again.');
        }
    }

    $conn->commit();
    $_SESSION['cart'] = [];
    $_SESSION['order_ids'][] = $orderId;
    $_SESSION['order_message'] = 'Order placed and payment completed successfully.';
    unset($_SESSION['checkout_old']);

    go_to('../order/history.php');
} catch (Throwable $exception) {
    $conn->rollback();
    $_SESSION['checkout_errors'] = [$exception->getMessage()];
    go_to('checkout.php');
}
