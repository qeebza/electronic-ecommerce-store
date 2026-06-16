<?php 
include '../db/config.php'; // Include connection instance
include '../includes/header.php'; 

// 1. Handle Delete Action securely if requested via link click
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $delete_sql = "DELETE FROM products WHERE product_id = ?";
    if ($stmt = mysqli_prepare($conn, $delete_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    // Refresh page to clear out query parameters from URL
    header("Location: products.php");
    exit;
}

// 2. Query all existing products from newest to oldest
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Product Management</h1>
        <a class="btn" href="add_product.php" style="margin-top: 0;">+ Add New Product</a>
    </div>

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
                            <td>#<?php echo $row['product_id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><span style="background: #f3f4f6; padding: 2px 8px; border-radius: 4px; font-size: 0.85em;"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td>RM <?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <?php if ($row['stock'] <= 5): ?>
                                    <span style="color: #d97706; font-weight: bold;"><?php echo $row['stock']; ?> (Low Stock)</span>
                                <?php else: ?>
                                    <span style="color: #059669;"><?php echo $row['stock']; ?> available</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="products.php?delete_id=<?php echo $row['product_id']; ?>" 
                                   onclick="return confirmAction('Are you completely sure you want to delete this product? This cannot be undone.')" 
                                   style="color: #ef4444; text-decoration: none; font-weight: bold; margin-left: 10px;">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #6b7280;">
                            No items found in your store database. Click "+ Add New Product" to stock your store!
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
