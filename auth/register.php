<?php include '../includes/header.php'; ?>
<main class="container">
    <div class="form-box">
        <h1>Register</h1>
        <form action="process_register.php" method="post">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter full name" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <label>Account Type</label>
            <select name="role">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Register</button>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
