<?php
include '../includes/header.php';
include '../db/config.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /electronic-ecommerce-store/auth/login.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $allowed  = ['Pending', 'Processing', 'Completed', 'Cancelled'];
    $status   = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'Pending';

    $stmt = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE order_id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: orders.php');
    exit;
}

// Fetch all orders with the name saved at checkout.
$result = mysqli_query($conn,
    "SELECT order_id, customer_name AS customer, total_amount, status, created_at
     FROM orders
     ORDER BY created_at DESC");
?>
<main class="container">
    <h1>Order Management</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>#<?php echo $row['order_id']; ?></td>
            <td><?php echo htmlspecialchars($row['customer']); ?></td>
            <td>RM <?php echo number_format($row['total_amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                    <select name="status">
                        <?php foreach (['Pending','Processing','Completed','Cancelled'] as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $row['status'] === $s ? 'selected' : ''; ?>>
                                <?php echo $s; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
<?php include '../includes/footer.php'; ?>
