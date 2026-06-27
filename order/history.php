<?php
require_once '../db/config.php';
require_once '../includes/cart_functions.php';

$userId = require_login('../auth/login.php');

include '../includes/header.php';

$stmt = $pdo->prepare(
    'SELECT * FROM orders
     WHERE user_id = ?
     ORDER BY created_at DESC'
);
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

$itemStmt = $pdo->prepare(
    'SELECT product_name, quantity, price
     FROM order_items
     WHERE order_id = ?
     ORDER BY item_id'
);
$message = $_SESSION['order_message'] ?? '';
unset($_SESSION['order_message']);
?>
<main class="container">
    <h1>Order History</h1>

    <?php if ($message !== ''): ?>
        <p><?php echo escape($message); ?></p>
    <?php endif; ?>

    <?php if (!$orders): ?>
        <div class="card">
            <p>No orders found.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($orders as $order): ?>
        <?php
        $orderId = (int) $order['order_id'];
        $itemStmt->execute([$orderId]);
        $orderItems = $itemStmt->fetchAll();
        ?>
        <div class="card">
            <h2>Order #<?php echo $orderId; ?></h2>
            <p>Date: <?php echo escape(date('d M Y', strtotime($order['created_at']))); ?></p>
            <p>Status: <?php echo escape($order['status']); ?></p>
            <p>Payment Method: <?php echo escape(ucwords(str_replace('_', ' ', $order['payment_method']))); ?></p>
            <p>Payment Status: <?php echo escape($order['payment_status']); ?></p>
            <p>Transaction Reference: <?php echo escape($order['transaction_reference']); ?></p>
            <p>Shipping Address: <?php echo escape($order['shipping_address']); ?></p>

            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?php echo escape($item['product_name']); ?></td>
                        <td><?php echo (int) $item['quantity']; ?></td>
                        <td><?php echo money((float) $item['price']); ?></td>
                        <td><?php echo money((float) $item['price'] * (int) $item['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p><strong>Total: <?php echo money((float) $order['total_amount']); ?></strong></p>
        </div>
    <?php endforeach; ?>
</main>
<?php include '../includes/footer.php'; ?>
