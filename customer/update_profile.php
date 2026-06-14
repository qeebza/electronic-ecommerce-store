<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$username = trim($_POST['username'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');

// Basic validation
if (empty($username)) {
    die("Username is required.");
}

try {
    // Update ONLY safe fields
    $stmt = $pdo->prepare("
        UPDATE users 
        SET username = ?, phone = ?, address = ?
        WHERE user_id = ?
    ");
    $stmt->execute([$username, $phone, $address, $user_id]);

    header("Location: profile.php?updated=1");
    exit;

} catch (PDOException $e) {
    die("Update failed: " . $e->getMessage());
}