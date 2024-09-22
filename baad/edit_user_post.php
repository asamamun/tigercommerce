<?php
session_start();
require 'config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if post ID is provided
if (!isset($_GET['id'])) {
    header('Location: profile.php?error=No+post+ID+provided');
    exit();
}

$post_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch post details
$post_result = $conn->query("SELECT id, photo, text_content FROM posts WHERE id = $post_id AND user_id = $user_id");
if ($post_result->num_rows != 1) {
    header('Location: profile.php?error=Post+not+found');
    exit();
}

$post = $post_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text_content = $_POST['text_content'];
    $photo = $_FILES['photo'];

    // Handle photo upload if a new photo is provided
    if ($photo['size'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo["name"]);
        move_uploaded_file($photo["tmp_name"], $target_file);
        //delete the old file
        unlink($post['photo']);

    } else {
        $target_file = $post['photo'];
    }

    $stmt = $conn->prepare("UPDATE posts SET text_content = ?, photo = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $text_content, $target_file, $post_id, $user_id);

    if ($stmt->execute()) {
        header('Location: profile.php?message=Post+updated+successfully');
    } else {
        header('Location: edit_user_post.php?id=' . $post_id . '&error=Failed+to+update+post');
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
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Post</h2>

    <!-- Display error messages -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="text_content" class="form-label">Text Content</label>
                <textarea class="form-control" id="text_content" name="text_content" rows="4" required><?php echo htmlspecialchars($post['text_content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
                <img src="<?php echo htmlspecialchars($post['photo']); ?>" alt="Current Photo" style="width: 100px; height: auto;" class="mt-2">
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap core JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>