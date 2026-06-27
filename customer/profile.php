<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT username, email, phone, address FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}
?>

<?php include '../includes/header.php'; ?>

<script src="/electronic-ecommerce-store/assets/js/confirmed.js"></script>
<main class="container">
    <div class="dashboard-box">
        <h1>User Profile</h1>

        <!-- Profile Update Form -->
        <form onsubmit="return confirmAction();" action="update_profile.php" method="POST">
            <p>
                <label>Username:</label><br>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </p>

            <p>
                <label>Email (read-only):</label><br>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            </p>

             <p>
                <label>Phone:</label><br>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </p>

            <p>
                <label>Address:</label><br>
                <textarea name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </p>

            <button type="submit" class="btn">Update Profile</button>
        </form>

        <div class="profile-actions">
            <a class="btn" href="../order/history.php">View Order History</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
