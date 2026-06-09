<?php include '../includes/header.php'; ?>
<main class="container">
    <h1>Product Listing</h1>
    <form method="get" class="card">
        <label>Search Product</label>
        <input type="text" name="search" placeholder="Search laptops, phones, accessories...">
        <button type="submit">Search</button>
    </form>

    <div class="grid">
        <div class="card">
            <div class="product-img">Product Image</div>
            <h3>Sample Laptop</h3>
            <p>RM 2,999.00</p>
            <a class="btn" href="details.php?id=1">View Details</a>
        </div>
        <div class="card">
            <div class="product-img">Product Image</div>
            <h3>Sample Smartphone</h3>
            <p>RM 1,499.00</p>
            <a class="btn" href="details.php?id=2">View Details</a>
        </div>
        <div class="card">
            <div class="product-img">Product Image</div>
            <h3>Sample Headset</h3>
            <p>RM 199.00</p>
            <a class="btn" href="details.php?id=3">View Details</a>
        </div>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
