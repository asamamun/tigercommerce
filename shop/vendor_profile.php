<?php
include 'db.php';

// Fetch vendor info (hardcoded vendor ID for simplicity)
$vendor_id = 1;
$stmt = $conn->prepare("SELECT * FROM vendors WHERE id = :id");
$stmt->execute(['id' => $vendor_id]);
$vendor = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Vendor Profile</h2>
        <form action="update_profile.php" method="POST">
            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="<?= $vendor['company_name'] ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"><?= $vendor['description'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
