<?php
require '../../config/database.php';

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
            // Check if we already have 5 images
            $result = $conn->query("SELECT COUNT(*) as count FROM carousel_images");
            $row = $result->fetch_assoc();
            $count = $row['count'];

            if ($count >= 5) {
                // Delete the oldest image
                $result = $conn->query("SELECT file_path FROM carousel_images ORDER BY id ASC LIMIT 1");
                $row = $result->fetch_assoc();
                $oldestImage = $row['file_path'];
                unlink($uploadDir . $oldestImage);

                $conn->query("DELETE FROM carousel_images ORDER BY id ASC LIMIT 1");
            }

            // Insert new image
            $stmt = $conn->prepare("INSERT INTO carousel_images (title, url, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $url, $fileName);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $uploadDir = '../../uploads/';
    $id = $_GET['delete'];
    $result = $conn->query("SELECT file_path FROM carousel_images WHERE id = $id");
    $row = $result->fetch_assoc();
    $filePath = $row['file_path'];

    if (unlink($uploadDir . $filePath)) {
        $conn->query("DELETE FROM carousel_images WHERE id = $id");
    }
}

// Handle image editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $url = $_POST['edit_url'];

    $stmt = $conn->prepare("UPDATE carousel_images SET title = ?, url = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $url, $id);
    $stmt->execute();
    $stmt->close();
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

            <!-- Button to trigger the upload modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-upload"></i> Upload Image
            </button>

            <!-- Upload Modal -->
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="display.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="url" class="form-label">URL</label>
                                    <input type="text" class="form-control" id="url" name="url" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image (JPG, PNG or GIF, max 5MB)</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/gif" required>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <h3>Current Carousel Images</h3>
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="../../uploads/<?= $image['file_path'] ?>" class="card-img-top img-fluid" alt="Carousel Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= $image['title'] ?></h5>
                                <p class="card-text"><?= $image['url'] ?></p>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $image['id'] ?>"><i class="bi bi-pencil-square"></i> Edit</button>
                                <a href="display.php?delete=<?= $image['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this image?');"><i class="bi bi-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $image['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $image['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $image['id'] ?>">Edit Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="display.php" method="post">
                                        <input type="hidden" name="edit_id" value="<?= $image['id'] ?>">
                                        <div class="mb-3">
                                            <label for="edit_title" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="edit_title" name="edit_title" value="<?= $image['title'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_url" class="form-label">URL</label>
                                            <input type="text" class="form-control" id="edit_url" name="edit_url" value="<?= $image['url'] ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>