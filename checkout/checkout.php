<?php
require_once '../db/config.php';
require_once '../includes/cart_functions.php';

require_login('../auth/login.php');

include '../includes/header.php';

$items = get_cart_items($pdo);
if (!$items) {
    set_message('Your cart is empty.');
    go_to('../cart/cart.php');
}

$total = calculate_total($items);
$errors = $_SESSION['checkout_errors'] ?? [];
$old = $_SESSION['checkout_old'] ?? [];
unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);
?>
<main class="container">
    <div class="form-box">
        <h1>Checkout</h1>

        <?php foreach ($errors as $error): ?>
            <p><?php echo escape($error); ?></p>
        <?php endforeach; ?>

        <form action="process_checkout.php" method="post">
            <label>Full Name</label>
            <input type="text" name="name" maxlength="100"
                   value="<?php echo escape($old['name'] ?? ''); ?>" required>

            <label>Email</label>
            <input type="email" name="email" maxlength="100"
                   value="<?php echo escape($old['email'] ?? ''); ?>" required>

            <label>Shipping Address</label>
            <textarea name="address" rows="4" maxlength="500" required><?php echo escape($old['address'] ?? ''); ?></textarea>

            <label>Payment Method</label>
            <select name="payment_method" required>
                <option value="card">Credit / Debit Card</option>
                <option value="online_banking">Online Banking</option>
                <option value="ewallet">E-Wallet</option>
            </select>

            <h2>Order Summary</h2>
            <?php foreach ($items as $item): ?>
                <p>
                    <?php echo escape($item['name']); ?>
                    x <?php echo (int) $item['quantity']; ?>
                    = <?php echo money((float) $item['subtotal']); ?>
                </p>
            <?php endforeach; ?>
            <p><strong>Total: <?php echo money($total); ?></strong></p>

            <button type="submit">Place Order</button>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
