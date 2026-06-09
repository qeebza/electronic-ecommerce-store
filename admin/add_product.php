<?php include '../includes/header.php'; ?>
<main class="container">
    <div class="form-box">
        <h1>Add Product</h1>
        <form method="post">
            <label>Product Name</label>
            <input type="text" name="name" required>

            <label>Description</label>
            <textarea name="description" rows="4"></textarea>

            <label>Price</label>
            <input type="number" name="price" step="0.01" required>

            <label>Stock</label>
            <input type="number" name="stock" required>

            <button type="submit">Save Product</button>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
