<?php
include '../includes/header.php';
include '../db/config.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /electronic-ecommerce-store/auth/login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: products.php');
    exit;
}

$errors = [];

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = $_POST['price'] ?? '';
    $stock       = $_POST['stock'] ?? '';
    $category    = trim($_POST['category'] ?? '');

    if ($name === '') $errors[] = 'Product name is required.';
    if (!is_numeric($price) || $price < 0) $errors[] = 'Price must be a valid positive number.';
    if (!is_numeric($stock) || intval($stock) < 0) $errors[] = 'Stock must be a valid non-negative number.';
    if ($category === '') $errors[] = 'Category is required.';

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn,
            "UPDATE products SET name=?, description=?, price=?, stock=?, category=? WHERE product_id=?");
        mysqli_stmt_bind_param($stmt, 'ssdisi', $name, $description, $price, $stock, $category, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: products.php?success=1');
        exit;
    }
}

// Fetch existing product
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<main class="container">
    <div class="form-box">
        <h1>Edit Product</h1>

        <?php foreach ($errors as $err): ?>
            <p style="color:red;"><?php echo htmlspecialchars($err); ?></p>
        <?php endforeach; ?>

        <form method="post">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $product['name']); ?>" required>

            <label>Description</label>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($_POST['description'] ?? $product['description']); ?></textarea>

            <label>Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($_POST['category'] ?? $product['category']); ?>" required>

            <label>Price (RM)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['price'] ?? $product['price']); ?>" required>

            <label>Stock</label>
            <input type="number" name="stock" min="0" value="<?php echo htmlspecialchars($_POST['stock'] ?? $product['stock']); ?>" required>

            <button type="submit">Update Product</button>
            <a href="products.php" style="margin-left:12px;">Cancel</a>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
