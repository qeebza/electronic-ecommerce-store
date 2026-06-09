<?php include '../includes/header.php'; ?>
<main class="container">
    <div class="form-box">
        <h1>Login</h1>
        <form action="process_login.php" method="post">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit">Login</button>
        </form>
        <p>Do not have an account? <a href="register.php">Register here</a></p>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
