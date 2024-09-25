<?php
require '../../config/database.php';
// require '../../config/functions.php';

// // Check if user is logged in and is an admin
// if (!is_admin()) {
//     header("Location: ../login.php");
//     exit();
// }

// $message = '';

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $uploadDir = '../../uploads/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $title = $_POST['title'];
    $url = $_POST['url'];

    if ($_FILES['image']['error'] == 0 && in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= $maxFileSize) {
        $fileName = uniqid() . '_' . $_FILES['image']['name'];
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // Check if we already have 3 images
            $result = $conn->query("SELECT COUNT(*) as count FROM carousel_images");
            $row = $result->fetch_assoc();
            $count = $row['count'];

            if ($count >= 5) {
                // Delete the oldest image
                $result = $conn->query("SELECT file_path FROM carousel_images ORDER BY id ASC LIMIT 1");
                $row = $result->fetch_assoc();
                $oldestImage = $row['file_path'];
                unlink($oldestImage);

                $conn->query("DELETE FROM carousel_images ORDER BY id ASC LIMIT 1");
            }

            // Insert new image
            $stmt = $conn->prepare("INSERT INTO carousel_images (title,url,file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss",$title,$url, $fileName);
            $stmt->execute();
            $stmt->close();

            $message = "Image uploaded successfully.";
        } else {
            $message = "Failed to upload image.";
        }
    } else {
        $message = "Invalid file. Please upload a JPG, PNG or GIF image under 5MB.";
    }
}

// Fetch current carousel images
$result = $conn->query("SELECT * FROM carousel_images ORDER BY id DESC");
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}
?>

<?php include('../partials/header.php'); ?>
<?php include('../partials/navbar.php'); ?>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <?php include('../partials/sidebar.php'); ?>
        </div>
        
        <div class="col-lg-9 col-md-8">
            <h2 class="my-4">Manage Carousel Images</h2>

            <?php if (isset($message)): ?>
                <div class="alert alert-info"><?= $message??'' ?></div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data" class="mb-4">
               <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
               </div>
               <div class="mb-3">
                <label for="url" class="form-label">URL <span class="text-danger"></label>
                <input type="url" class="form-control" id="url" name="url">
               </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image (JPG, PNG or GIF, max 5MB)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>

            <h3>Current Carousel Images</h3>
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4 mb-3">
                        <h4><?= $image['title'] ?></h4>
                        <p><?= $image['url'] ?></p>
                        <img src="../../uploads/<?= $image['file_path'] ?>" class="img-fluid" alt="Carousel Image">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>