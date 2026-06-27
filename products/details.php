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
$imagePath = !empty($product['image_path'])
    ? preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $product['image_path'])
    : '';

// Redirect out or message user if targeting invalid entities
if (!$product) {
    echo "<main class='container'><div class='card'><h1>Product Not Found</h1><p>The requested product does not exist.</p><a href='list.php' class='btn'>Back to Marketplace</a></div></main>";
    include '../includes/footer.php';
    exit;
}
?>

<main class="container">
    <div class="card product-details">
        <?php if ($imagePath !== ''): ?>
            <div class="product-details-media">
                <img class="product-img product-photo"
                     src="/electronic-ecommerce-store/<?php echo htmlspecialchars($imagePath); ?>"
                     alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        <?php else: ?>
            <div class="product-img product-details-placeholder">
                <?php echo htmlspecialchars($product['category'] ?? 'Electronics'); ?> Image
            </div>
        <?php endif; ?>
        
        <div class="product-details-info">
            <div>
                <span class="product-tag">
                    <?php echo htmlspecialchars($product['category']); ?>
                </span>
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <p class="product-description">
                    <?php echo htmlspecialchars($product['description'] ? $product['description'] : 'No descriptive specification provided for this product.'); ?>
                </p>
            </div>

            <div class="product-details-actions">
                <p class="product-price">
                    <strong>RM <?php echo number_format($product['price'], 2); ?></strong>
                </p>
                
                <p class="product-availability">
                    <strong>Availability Status:</strong> 
                    <?php if ($product['stock'] > 0): ?>
                        <span class="stock-in">In Stock (<?php echo $product['stock']; ?> units remaining)</span>
                    <?php else: ?>
                        <span class="stock-out">Out of Stock</span>
                    <?php endif; ?>
                </p>
                
                <?php if ($product['stock'] > 0): ?>
                    <a class="btn product-action-btn" href="../cart/add_to_cart.php?id=<?php echo $product['product_id']; ?>">Add to Cart</a>
                <?php else: ?>
                    <button class="btn btn-secondary product-action-btn btn-disabled" disabled>Sold Out</button>
                <?php endif; ?>
                
                <a href="list.php" class="btn btn-secondary product-action-btn product-back-btn">Back to Catalog</a>
            </div>
        </div>
    </div>
</main>

<?php 
mysqli_stmt_close($stmt);
include '../includes/footer.php'; 
?>
