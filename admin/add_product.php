<?php 
include '../db/config.php'; // Include database connection
include '../includes/header.php'; 

$message = '';

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = trim($_POST['category']); // Added to match schema

    // Simple validation
    if (!empty($name) && $price >= 0 && $stock >= 0 && !empty($category)) {
        // SQL query using prepared statements for security
        $sql = "INSERT INTO products (name, description, price, stock, category) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssdis", $name, $description, $price, $stock, $category);
            
            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='card' style='background: #d1fae5; color: #065f46; border: 1px solid #10b981;'>Product added successfully! <a href='products.php'>View All Products</a></div>";
            } else {
                $message = "<div class='card' style='background: #fee2e2; color: #991b1b; border: 1px solid #ef4444;'>Database Error: " . mysqli_error($conn) . "</div>";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = "<div class='card' style='background: #fffbe6; color: #9a6700; border: 1px solid #fadb14;'>Please fill in all fields with valid information.</div>";
    }
}
?>

<main class="container">
    <div class="form-box">
        <h1>Add Product</h1>
        
        <?php echo $message; ?>

        <form method="post" action="add_product.php">
            <label>Product Name</label>
            <input type="text" name="name" required placeholder="e.g., Sony WH-1000XM5">

            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Laptops">Laptops</option>
                <option value="Smartphones">Smartphones</option>
                <option value="Accessories">Accessories</option>
                <option value="Monitors">Monitors</option>
                <option value="Tablets">Tablets</option>
            </select>

            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Enter specifications or feature bullet points..."></textarea>

            <label>Price (RM)</label>
            <input type="number" name="price" step="0.01" min="0" required placeholder="0.00">

            <label>Stock Quantity</label>
            <input type="number" name="stock" min="0" required placeholder="0">

            <button type="submit">Save Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
