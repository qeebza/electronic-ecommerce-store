<?php
session_start();
require '../db/config.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';



if (empty($email) || empty($password)) {
    header('Location: login.php?login=empty&email=' . urlencode($email));
    exit;
}

// Get user from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {

    // IMPORTANT: reset session safely
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    header('Location: ../customer/profile.php?login=success');
    exit;

} else {
    header('Location: login.php?login=failed&email=' . urlencode($email));
    exit;
}
