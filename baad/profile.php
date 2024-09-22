<?php
session_start();
require 'config/database.php';
require 'vendor/autoload.php';

use Carbon\Carbon;
// use Intervention\Image\ImageManager; (not warking)

if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit();
}
$id = $_SESSION['user_id'];

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
    $profile_pic_name = uniqid() . '_' . $_FILES['profile_pic']['name'];
    $upload_dir = 'uploads/profile_pics/';

    // Resize the image
    list($width, $height) = getimagesize($profile_pic_tmp);
    $new_width = 150;
    $new_height = 150;
    $image_p = imagecreatetruecolor($new_width, $new_height);
    $image = imagecreatefromjpeg($profile_pic_tmp);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Save the resized image
    $resized_image_path = $upload_dir . $profile_pic_name;
    imagejpeg($image_p, $resized_image_path, 100);

    // Clean up
    imagedestroy($image_p);
    imagedestroy($image);

    // Ensure the directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $upload_path = $resized_image_path;

    if (move_uploaded_file($profile_pic_tmp, $upload_path)) {
        $updateQuery = "UPDATE users SET profile_pic = '$upload_path' WHERE id = '$id'";
        $conn->query($updateQuery);
        $_SESSION['message'] = 'Profile picture updated successfully!';
    } else {
        $_SESSION['message'] = 'Profile picture upload failed.';
    }
}
// Fetch user details including profile picture and username
$userQuery = "SELECT username, profile_pic FROM users WHERE id = '$id'";
$userResult = $conn->query($userQuery);
$user = $userResult->fetch_assoc();

// Fetch user posts
$selectQuery = "SELECT * FROM posts WHERE user_id = '$id' and deleted is null";
$result = $conn->query($selectQuery);

?>
<?php include 'includes/head.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5">
        <?php
        // If $_SESSION['message'] has message show in dismissable alert
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <div class="row">
            <div class="col-12 text-center">
                <h1>Profile</h1>
                <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="rounded-circle" width="150" height="150">
                <form action="profile.php" method="post" enctype="multipart/form-data" class="mt-3">
                    <input type="file" name="profile_pic" accept="image/*" class="form-control mb-2">
                    <button type="submit" class="btn btn-primary">Upload Profile Picture</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <?php include 'includes/card.php'; ?>
            <?php } ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>