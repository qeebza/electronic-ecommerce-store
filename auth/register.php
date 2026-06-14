<?php include '../includes/header.php'; ?>

<main class="container">
    <div class="form-box">
        <h1>Register</h1>

        <form action="process_register.php" method="post" autocomplete="off">

            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter full name" required>

            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="Enter phone number">

            <label>Password</label>
            <input type="password" name="password" placeholder="At least 6 characters" minlength="6" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>

            <button type="submit">Register</button>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>