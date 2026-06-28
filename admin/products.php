<?php
include '../includes/header.php';
include '../db/config.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /electronic-ecommerce-store/auth/login.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: products.php');
    exit;
}

// Fetch all products
$result = mysqli_query($conn, "SELECT product_id, name, price, stock, category FROM products ORDER BY product_id DESC");
?>
<main class="container">
    <h1>Product Management</h1>
    <a class="btn" href="add_product.php">+ Add Product</a>

    <?php if (isset($_GET['success'])): ?>
        <p style="color:green; margin-top:10px;">Product saved successfully.</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['category']); ?></td>
            <td>RM <?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>">Edit</a> |
                <a href="products.php?delete=<?php echo $row['product_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
<?php include '../includes/footer.php'; ?>
