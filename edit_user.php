<?php
session_start();
require 'config/database.php';

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php?error=No+user+ID+provided');
    exit();
}

$user_id = intval($_GET['id']);

// Fetch user details
$user_result = $conn->query("SELECT id, username, email, is_admin FROM users WHERE id = $user_id");
if ($user_result->num_rows != 1) {
    header('Location: dashboard.php?error=User+not+found');
    exit();
}

$user = $user_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, is_admin = ? WHERE id = ?");
    $stmt->bind_param("ssii", $username, $email, $is_admin, $user_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php?message=User+updated+successfully');
    } else {
        header('Location: edit_user.php?id=' . $user_id . '&error=Failed+to+update+user');
    }

    $stmt->close();
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>`
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit User</h2>

    <!-- Display error messages -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" <?php echo $user['is_admin'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_admin">Admin</label>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        <a href=""></a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap core JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>