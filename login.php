<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ? limit 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            //get vendor id
            if ($role == "vendor") {

                $stmt = $conn->prepare("SELECT id FROM vendors WHERE user_id = ? limit 1");
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($vendor_id);
                $stmt->fetch();
                $_SESSION['vendor_id'] = $vendor_id;
            }

            if ($role == "admin") {
                header('Location: admin/index.php');
            } elseif ($role == "vendor") {
                header('Location: shop/index.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<?php require "partials/header.php" ?>
</head>
<body>
<?php require "partials/navbar.php" ?>
    <div class="container main-content">
        <div class="container mt-5">
            <h2 class="text-center mb-4">Login</h2>
            <form action="login.php" method="POST" class="mx-auto p-4 border rounded-3 shadow-sm" style="max-width: 400px;">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <?php if ($error): ?>
                    <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php require "partials/footer.php" ?>   
    </body>
</html>
