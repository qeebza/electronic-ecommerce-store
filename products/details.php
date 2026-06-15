<?php 
// Initialize requirements
include '../db/config.php'; 
include '../includes/header.php'; 

// Fetch URL context route parameters safely
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query database using safe prepared queries
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Redirect out or message user if targeting invalid entities
if (!$product) {
    echo "<main class='container'><div class='card'><h1>Product Not Found</h1><p>The requested product does not exist.</p><a href='list.php' class='btn'>Back to Marketplace</a></div></main>";
    include '../includes/footer.php';
    exit;
}
?>

<main class="container">
    <div class="card" style="display: flex; gap: 30px; flex-wrap: wrap; align-items: start;">
        <div class="product-img" style="flex: 1; min-width: 300px; height: 300px; font-size: 24px;">
            <?php echo htmlspecialchars($product['category'] ?? 'Electronics'); ?> Image
        </div>
        
        <div style="flex: 1; min-width: 300px;">
            <span style="background: #e5e7eb; padding: 4px 10px; border-radius: 20px; font-size: 0.85em; font-weight: bold; color: #4b5563;">
                <?php echo htmlspecialchars($product['category']); ?>
            </span>
            <h1 style="margin-top: 10px;"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <p style="margin: 20px 0; color: #4b5563; white-space: pre-line;">
                <?php echo htmlspecialchars($product['description'] ? $product['description'] : 'No descriptive specification provided for this product.'); ?>
            </p>
            
            <p style="font-size: 1.5em; color: #2563eb; margin-bottom: 10px;">
                <strong>RM <?php echo number_format($product['price'], 2); ?></strong>
            </p>
            
            <p style="margin-bottom: 20px;">
                <strong>Availability Status:</strong> 
                <?php if ($product['stock'] > 0): ?>
                    <span style="color: #10b981; font-weight: bold;">In Stock (<?php echo $product['stock']; ?> units remaining)</span>
                <?php else: ?>
                    <span style="color: #ef4444; font-weight: bold;">Out of Stock</span>
                <?php endif; ?>
            </p>
            
            <?php if ($product['stock'] > 0): ?>
                <a class="btn" href="../cart/add_to_cart.php?id=<?php echo $product['product_id']; ?>" style="padding: 12px 24px; font-size: 1.1em;">Add to Cart</a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled style="cursor: not-allowed; padding: 12px 24px; font-size: 1.1em;">Sold Out</button>
            <?php endif; ?>
            
            <a href="list.php" class="btn btn-secondary" style="padding: 12px 24px; font-size: 1.1em; margin-left: 10px;">Back to Catalog</a>
        </div>
    </div>
</main>

<?php 
mysqli_stmt_close($stmt);
include '../includes/footer.php'; 
?>
