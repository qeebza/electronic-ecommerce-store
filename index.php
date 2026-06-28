<?php include 'includes/header.php'; ?>
<main class="container">
    <!-- Upgraded Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); padding: 4rem 2rem; border-radius: 12px; text-align: center; margin-bottom: 3rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: #2c3e50;">Welcome to Electronic Store</h1>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; color: #34495e;">Your ultimate destination for the latest gadgets, devices, and electronic essentials.</p>
        <a class="btn" href="products/list.php" style="padding: 12px 30px; font-size: 1.1rem; border-radius: 6px; display: inline-block;">Browse All Products</a>
    </section>

    <section>
        <h2 style="margin-bottom: 1.5rem; text-align: center;">Product Categories</h2>
        
        <!-- Flex container to force a single horizontal row -->
        <div style="display: flex; gap: 15px; justify-content: space-between; align-items: stretch; flex-wrap: nowrap;">
            
            <a class="card" href="products/list.php?category=Laptops" style="flex: 1; text-decoration: none; color: inherit; text-align: center; display: flex; flex-direction: column; justify-content: center; padding: 15px;">
                <img src="assets/images/products/apple-macbook-air-13-m3.webp" alt="Laptops" style="width: 100%; height: 100px; object-fit: contain; margin-bottom: 10px;">
                <h3 style="margin: 0; font-size: 1rem;">Laptops</h3>
            </a>

            <a class="card" href="products/list.php?category=Smartphones" style="flex: 1; text-decoration: none; color: inherit; text-align: center; display: flex; flex-direction: column; justify-content: center; padding: 15px;">
                <img src="assets/images/products/apple-iphone-15.webp" alt="Smartphones" style="width: 100%; height: 100px; object-fit: contain; margin-bottom: 10px;">
                <h3 style="margin: 0; font-size: 1rem;">Smartphones</h3>
            </a>

            <a class="card" href="products/list.php?category=Tablets" style="flex: 1; text-decoration: none; color: inherit; text-align: center; display: flex; flex-direction: column; justify-content: center; padding: 15px;">
                <img src="assets/images/products/honor-pad-v9.webp" alt="Tablets" style="width: 100%; height: 100px; object-fit: contain; margin-bottom: 10px;">
                <h3 style="margin: 0; font-size: 1rem;">Tablets</h3>
            </a>

            <a class="card" href="products/list.php?category=Audio" style="flex: 1; text-decoration: none; color: inherit; text-align: center; display: flex; flex-direction: column; justify-content: center; padding: 15px;">
                <img src="assets/images/products/anker-soundcore-liberty-4-nc.webp" alt="Audio Devices" style="width: 100%; height: 100px; object-fit: contain; margin-bottom: 10px;">
                <h3 style="margin: 0; font-size: 1rem;">Audio Devices</h3>
            </a>

            <a class="card" href="products/list.php?category=PC" style="flex: 1; text-decoration: none; color: inherit; text-align: center; display: flex; flex-direction: column; justify-content: center; padding: 15px;">
                <img src="assets/images/products/asus-tuf-gaming-vg27aq3a.webp" alt="PC Accessories" style="width: 100%; height: 100px; object-fit: contain; margin-bottom: 10px;">
                <h3 style="margin: 0; font-size: 1rem;">PC Accessories</h3>
            </a>

        </div>
    </section>
</main>
<?php include 'includes/footer.php'; ?>
