<?php
session_start();
require 'C:\xampp\htdocs\electronic-ecommerce-store\db\config.php'; // your DB connection file

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

// Get form data
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');

// Basic validation
    
if (empty($username) || empty($email) || empty($password) || empty($confirm) || empty($name) || empty($phone)) {
    die("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

if ($password !== $confirm) {
    die("Passwords do not match.");
}

if (strlen($password) < 6) {
    die("Password must be at least 6 characters.");
}

try {
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);

    if ($stmt->fetch()) {
        die("Username or email already exists.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword, $name, $phone]);

    // Redirect to login
    header("Location: login.php?registered=1");
    exit;

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}