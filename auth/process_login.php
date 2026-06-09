<?php
session_start();

// Placeholder login process. Add database validation later.
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($email) && !empty($password)) {
    $_SESSION['user_id'] = 1;
    $_SESSION['role'] = 'customer';
    header('Location: ../customer/profile.php');
    exit;
}

header('Location: login.php');
exit;
?>
