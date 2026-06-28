<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../db/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /electronic-ecommerce-store/auth/login.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $category = trim($_POST['category'] ?? '');
    $imagePath = trim($_POST['image_path'] ?? '');

    if ($name === '') {
        $errors[] = 'Product name is required.';
    }
    if (!is_numeric($price) || $price < 0) {
        $errors[] = 'Price must be a valid positive number.';
    }
    if (!is_numeric($stock) || (int) $stock < 0) {
        $errors[] = 'Stock must be a valid non-negative number.';
    }
    if ($category === '') {
        $errors[] = 'Category is required.';
    }

    if (!$errors) {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO products (name, description, price, stock, category, image_path)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'ssdiss', $name, $description, $price, $stock, $category, $imagePath);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: products.php?success=1');
        exit;
    }
}

include '../includes/header.php';
?>

<main class="container">
    <div class="form-box">
        <h1>Add Product</h1>

        <?php foreach ($errors as $error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>

        <form method="post" action="add_product.php">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required placeholder="e.g., Sony WH-1000XM5">

            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <?php foreach (['Laptops', 'Smartphones', 'Accessories', 'Monitors', 'Tablets'] as $option): ?>
                    <option value="<?php echo $option; ?>" <?php echo (($_POST['category'] ?? '') === $option) ? 'selected' : ''; ?>>
                        <?php echo $option; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Image Path</label>
            <input type="text" name="image_path" value="<?php echo htmlspecialchars($_POST['image_path'] ?? ''); ?>" placeholder="assets/images/products/example.webp">

            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Enter specifications or feature bullet points..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

            <label>Price (RM)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required placeholder="0.00">

            <label>Stock Quantity</label>
            <input type="number" name="stock" min="0" value="<?php echo htmlspecialchars($_POST['stock'] ?? ''); ?>" required placeholder="0">

            <button type="submit">Save Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
