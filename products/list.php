<?php 
// Include database configuration and structural layouts
include '../db/config.php'; 
include '../includes/header.php'; 

// Fetch filtering parameters safely
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// 1. Fetch available categories dynamically for the filter dropdown
$cat_query = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''";
$cat_result = mysqli_query($conn, $cat_query);

// 2. Build SQL statement dynamically for products based on applied filters
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

// Prepare and execute statement
$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<main class="container">
    <h1>Product Listing</h1>
    
    <form method="get" class="card product-filter">
        <div class="product-filter-search">
            <label>Search Product</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search laptops, phones, accessories...">
        </div>
        
        <div class="product-filter-category">
            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                <?php while ($cat_row = mysqli_fetch_assoc($cat_result)): ?>
                    <option value="<?php echo htmlspecialchars($cat_row['category']); ?>" <?php echo ($category === $cat_row['category']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat_row['category']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="product-filter-actions">
            <button type="submit" class="btn-no-margin">Filter</button>
        </div>
    </form>

    <div class="grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <div class="card product-card">
                    <?php if (!empty($product['image_path'])): ?>
                        <img class="product-img product-photo"
                             src="/electronic-ecommerce-store/<?php echo htmlspecialchars($product['image_path']); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div class="product-img">
                            <?php echo htmlspecialchars($product['category'] ?? 'Product'); ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    </div>
                    <div class="product-card-actions">
                        <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                        <p><strong>RM <?php echo number_format($product['price'], 2); ?></strong></p>
                        <a class="btn" href="details.php?id=<?php echo $product['product_id']; ?>">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="card no-products">
                <p>No products match your searching criteria. Try adjusting your filters!</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php 
mysqli_stmt_close($stmt);
include '../includes/footer.php'; 
?>
