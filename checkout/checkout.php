<?php include '../includes/header.php'; ?>
<main class="container">
    <div class="form-box">
        <h1>Checkout</h1>
        <form action="process_checkout.php" method="post">
            <label>Full Name</label>
            <input type="text" name="name" required>

            <label>Shipping Address</label>
            <textarea name="address" rows="4" required></textarea>

            <label>Payment Method</label>
            <select name="payment_method">
                <option value="card">Credit / Debit Card</option>
                <option value="online_banking">Online Banking</option>
                <option value="ewallet">E-Wallet</option>
            </select>

            <button type="submit">Place Order</button>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
