<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require 'config/database.php';
use Intervention\Image\ImageManager ;
use Intervention\Image\Drivers\Gd\Driver;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text_content = $_POST['text_content'];
    $photo = '';

    // Handle photo upload if exists
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_name = uniqid() . '_' . $_FILES['photo']['name'];
        $upload_dir = 'uploads/' . $photo_name;

        if (move_uploaded_file($photo_tmp, $upload_dir)) {
            $photo = $upload_dir;
            // Resize the image 
            $image = new ImageManager(new Driver());
            $image->read($upload_dir)->scale(width: 800)->save();

        } else {
            $error = 'Photo upload failed.';
        }
    }

    // Insert the post into the database
    if ($text_content || $photo) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, photo, text_content) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $_SESSION['user_id'], $photo, $text_content);
        if ($stmt->execute()) {
            $success = 'Post uploaded successfully!';
        } else {
            $error = 'Error uploading post.';
        }
    } else {
        $error = 'Please provide either a photo or some text.';
    }
}
?>

<?php include 'includes/head.php'; ?>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Upload a Post</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="text_content" class="form-label">Share your thoughts</label>
            <textarea class="form-control" id="text_content" name="text_content" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Upload a photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
