<?php include '../includes/header.php'; ?>
<main class="container">
    <h1>Shopping Cart</h1>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>Sample Laptop</td>
            <td>RM 2,999.00</td>
            <td>1</td>
            <td>RM 2,999.00</td>
            <td><a href="#" onclick="return confirmAction('Remove this item?')">Remove</a></td>
        </tr>
    </table>
    <div class="card">
        <h2>Order Summary</h2>
        <p>Subtotal: RM 2,999.00</p>
        <p>Shipping: RM 0.00</p>
        <p><strong>Total: RM 2,999.00</strong></p>
        <a class="btn" href="../checkout/checkout.php">Proceed to Checkout</a>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
