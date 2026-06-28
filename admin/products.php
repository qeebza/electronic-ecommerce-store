<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../db/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /electronic-ecommerce-store/auth/login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = mysqli_prepare($conn, 'DELETE FROM products WHERE product_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location: products.php');
    exit;
}

$result = mysqli_query($conn, 'SELECT * FROM products ORDER BY product_id ASC');

include '../includes/header.php';
?>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Product Management</h1>
        <a class="btn" href="add_product.php" style="margin-top: 0;">+ Add Product</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <p style="color:green; margin-bottom:10px;">Product saved successfully.</p>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo (int) $row['product_id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><span style="background: #f3f4f6; padding: 2px 8px; border-radius: 4px; font-size: 0.85em;"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td>RM <?php echo number_format((float) $row['price'], 2); ?></td>
                            <td>
                                <?php if ((int) $row['stock'] <= 5): ?>
                                    <span style="color: #d97706; font-weight: bold;"><?php echo (int) $row['stock']; ?> (Low Stock)</span>
                                <?php else: ?>
                                    <span style="color: #059669;"><?php echo (int) $row['stock']; ?> available</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="edit_product.php?id=<?php echo (int) $row['product_id']; ?>">Edit</a>
                                |
                                <a href="products.php?delete=<?php echo (int) $row['product_id']; ?>"
                                   onclick="return confirm('Are you sure you want to delete this product?')"
                                   style="color: #ef4444; text-decoration: none; font-weight: bold;">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #6b7280;">
                            No items found in your store database. Click "+ Add Product" to stock your store.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
mysqli_free_result($result);
include '../includes/footer.php';
?>
