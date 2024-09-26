<?php
session_start();
require 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if the email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            // Hash the password and insert the new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $first_name, $last_name, $username, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now log in.';
            } else {
                $error = 'Error creating account. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>
<?php require "partials/header.php" ?>
</head>

<body>
    <?php require "partials/navbar.php" ?>
    <div class="container main-content">
        <div class="container mt-5">
            <h2 class="text-center mb-4">Create an Account</h2>
            <!-- Show success or error messages -->
            <?php if ($error): ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success mt-3"><?php echo $success; ?></div>
            <?php endif; ?>
            <form action="register.php" method="POST" class="mx-auto" style="max-width: 500px;">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required pattern="^[a-zA-Z0-9]{5,20}$" title="Username must be between 5 and 20 characters and only contain letters and numbers." placeholder="Enter username (5-20 characters)">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <!-- role option: vendor and customer in radio -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="vendor" value="vendor">
                        <label class="form-check-label" for="vendor">Vendor</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="customer" value="customer">
                        <label class="form-check-label" for="customer">Customer</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>

    <?php require "partials/footer.php" ?>
</body>

</html>