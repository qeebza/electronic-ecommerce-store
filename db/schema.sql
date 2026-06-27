CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart_items (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    shipping_address VARCHAR(500) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    payment_method ENUM('card', 'online_banking', 'ewallet') NOT NULL,
    payment_status VARCHAR(30) DEFAULT 'Pending',
    transaction_reference VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    product_name VARCHAR(150) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

INSERT INTO products (name, description, price, stock, category, image_path) VALUES
('Apple MacBook Air 13 M3', '13-inch MacBook Air with Apple M3 chip, 8GB unified memory, 256GB SSD, Liquid Retina display, and all-day battery life.', 4999.00, 8, 'Laptops', 'assets/images/products/apple-macbook-air-13-m3.webp'),
('Apple MacBook Pro 14 M3 Pro', '14-inch MacBook Pro with M3 Pro chip, 18GB unified memory, 512GB SSD, Liquid Retina XDR display, and pro performance.', 8999.00, 5, 'Laptops', 'assets/images/products/apple-macbook-pro-14-m3-pro.webp'),
('Dell XPS 13', 'Premium 13-inch ultrabook with Intel Core processor, 16GB RAM, 512GB SSD, slim design, and bright display.', 5999.00, 7, 'Laptops', 'assets/images/products/dell-xps-13.webp'),
('ASUS ROG Zephyrus G14', 'Compact gaming laptop with AMD Ryzen processor, NVIDIA GeForce RTX graphics, high refresh display, and portable chassis.', 7499.00, 6, 'Laptops', 'assets/images/products/asus-rog-zephyrus-g14.webp'),
('Lenovo ThinkPad X1 Carbon', 'Business laptop with lightweight carbon-fibre design, Intel Core processor, 16GB RAM, 512GB SSD, and strong keyboard comfort.', 6999.00, 9, 'Laptops', 'assets/images/products/lenovo-thinkpad-x1-carbon.webp'),

('Apple iPhone 15', 'iPhone 15 with 6.1-inch Super Retina XDR display, A16 Bionic chip, 128GB storage, USB-C, and advanced dual-camera system.', 4399.00, 14, 'Smartphones', 'assets/images/products/apple-iphone-15.webp'),
('Apple iPhone 15 Pro', 'iPhone 15 Pro with titanium design, A17 Pro chip, 128GB storage, Pro camera system, and USB-C connectivity.', 5499.00, 10, 'Smartphones', 'assets/images/products/apple-iphone-15-pro.webp'),
('Samsung Galaxy S24 Ultra', 'Flagship Android smartphone with 6.8-inch display, S Pen, 256GB storage, high-resolution camera, and Galaxy AI features.', 6299.00, 8, 'Smartphones', 'assets/images/products/samsung-galaxy-s24-ultra.webp'),
('Google Pixel 8 Pro', 'Google Pixel 8 Pro with Tensor chip, 128GB storage, advanced camera features, clean Android experience, and AI tools.', 4999.00, 9, 'Smartphones', 'assets/images/products/google-pixel-8-pro.webp'),
('Xiaomi 14', 'Compact flagship smartphone with Snapdragon processor, 256GB storage, AMOLED display, Leica camera system, and fast charging.', 3499.00, 12, 'Smartphones', 'assets/images/products/xiaomi-14.webp'),

('Apple AirPods Pro 2', 'Wireless earbuds with active noise cancellation, transparency mode, MagSafe charging case, and improved sound quality.', 1099.00, 18, 'Accessories', 'assets/images/products/apple-airpods-pro-2.webp'),
('Sony WH-1000XM5', 'Premium wireless noise-cancelling headphones with long battery life, lightweight design, and excellent sound quality.', 1799.00, 10, 'Accessories', 'assets/images/products/sony-wh-1000xm5.webp'),
('Logitech MX Master 3S', 'Wireless productivity mouse with quiet clicks, ergonomic shape, MagSpeed scroll wheel, and multi-device support.', 429.00, 20, 'Accessories', 'assets/images/products/logitech-mx-master-3s.webp'),
('Keychron K2 Mechanical Keyboard', 'Compact wireless mechanical keyboard with hot-swappable switches, Bluetooth support, and Mac/Windows compatibility.', 399.00, 16, 'Accessories', 'assets/images/products/keychron-k2-mechanical-keyboard.webp'),
('Anker 737 Power Bank', 'High-capacity portable power bank with fast USB-C charging, digital display, and support for laptops and smartphones.', 699.00, 15, 'Accessories', 'assets/images/products/anker-737-power-bank.webp');
