<?php
require_once '../db/config.php';
require_once '../includes/cart_functions.php';
include '../includes/header.php';

$items = get_cart_items($conn);
$total = calculate_total($items);
$message = get_message();
?>
<main class="container">
    <h1>Shopping Cart</h1>

    <?php if ($message !== ''): ?>
        <p><?php echo escape($message); ?></p>
    <?php endif; ?>

    <?php if (!$items): ?>
        <div class="card">
            <p>Your cart is empty.</p>
            <a class="btn" href="../products/list.php">Continue Shopping</a>
        </div>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo escape($item['name']); ?></td>
                    <td><?php echo money((float) $item['price']); ?></td>
                    <td>
                        <form action="update_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo (int) $item['product_id']; ?>">
                            <input type="number" name="quantity"
                                   value="<?php echo (int) $item['quantity']; ?>"
                                   min="1" max="<?php echo (int) $item['stock']; ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td><?php echo money((float) $item['subtotal']); ?></td>
                    <td>
                        <form action="update_cart.php" method="post"
                              onsubmit="return confirmAction('Remove this item?')">
                            <input type="hidden" name="product_id" value="<?php echo (int) $item['product_id']; ?>">
                            <input type="hidden" name="quantity" value="0">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="card">
            <h2>Order Summary</h2>
            <p><strong>Total: <?php echo money($total); ?></strong></p>
            <a class="btn" href="../checkout/checkout.php">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
