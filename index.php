<?php include 'includes/header.php'; ?>
<main class="container">
    <section class="hero">
        <h1>Welcome to Electronic Store</h1>
        <p>Blank starter homepage for the electronic e-commerce web application.</p>
        <a class="btn" href="products/list.php">Browse Products</a>
    </section>

    <section>
        <h2>Product Categories</h2>
        <div class="grid">
            <a class="card" href="products/list.php?category=Laptops" style="text-decoration: none; color: inherit; text-align: center; display: block;">
                <img src="assets/images/products/apple-macbook-air-13-m3.webp" alt="Laptops" style="width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px;">
                <h3 style="margin: 0;">Laptops</h3>
            </a>

            <a class="card" href="products/list.php?category=Smartphones" style="text-decoration: none; color: inherit; text-align: center; display: block;">
                <img src="assets/images/products/apple-iphone-15.webp" alt="Smartphones" style="width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px;">
                <h3 style="margin: 0;">Smartphones</h3>
            </a>

            <a class="card" href="products/list.php?category=Tablets" style="text-decoration: none; color: inherit; text-align: center; display: block;">
                <img src="assets/images/products/apple-ipad-air-11-m3.webp" alt="Tablets" style="width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px;">
                <h3 style="margin: 0;">Tablets</h3>
            </a>

            <a class="card" href="products/list.php?category=Audio" style="text-decoration: none; color: inherit; text-align: center; display: block;">
                <img src="assets/images/products/anker-soundcore-liberty-4-nc.webp" alt="Audio Devices" style="width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px;">
                <h3 style="margin: 0;">Audio Devices</h3>
            </a>

            <a class="card" href="products/list.php?category=PC" style="text-decoration: none; color: inherit; text-align: center; display: block;">
                <img src="assets/images/products/asus-tuf-gaming-vg27aq3a.webp" alt="Gaming Devices" style="width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px;">
                <h3 style="margin: 0;">PC Accesories</h3>
            </a>
        </div>
    </section>
</main>
<?php include 'includes/footer.php'; ?>
