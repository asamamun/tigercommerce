<?php
session_start();
require 'config/database.php';

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit();
}
?>

<?php include 'includes/head.php'; ?>

</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Admin Dashboard</h2>

    <!-- Display success or error messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Users Management -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Users</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users_result = $conn->query("SELECT id, username, email, is_admin FROM users");
                    while ($user = $users_result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $user['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                        echo '<td>' . ($user['is_admin'] ? 'Admin' : 'User') . '</td>';
                        echo '<td>
                                <a href="edit_user.php?id=' . $user['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                <a onclick="return confirm(\'Are you sure you want to delete this post?\')" href="delete_user.php?id=' . $user['id'] . '" class="btn btn-danger btn-sm">Delete</a>
                              </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Posts Management -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Posts</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Text Content</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $posts_result = $conn->query("SELECT id, photo, text_content, deleted FROM posts");
                    while ($post = $posts_result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $post['id'] . '</td>';
                        
                        // Display the photo if available
                        if (!empty($post['photo'])) {
                            echo '<td><img src="' . $post['photo'] . '" alt="Post Image" style="width: 100px; height: auto;"></td>';
                        } else {
                            echo '<td>No Image</td>';
                        }

                        // Display text content if available
                        echo '<td>' . htmlspecialchars($post['text_content']) . '</td>';
                        if (is_null($post['deleted'])) {
                            echo '<td><a href="delete_post.php?id=' . $post['id'] . '" class="btn btn-danger btn-sm">Delete</a></td>';
                        } else {
                            echo '<td><a href="restore_post.php?id=' . $post['id'] . '" class="btn btn-success btn-sm">Restore</a></td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap core JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>