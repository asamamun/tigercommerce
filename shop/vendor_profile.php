<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/database.php";

// Fetch vendor info (hardcoded vendor ID for simplicity)
$vendor_id = $_SESSION['user_id'];
$query = "SELECT * FROM vendors WHERE user_id = $vendor_id limit 1";
// echo $query;
$result = $conn->query($query);
$vendor = $result->fetch_assoc();
// var_dump($vendor);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = $_POST['company_name'];
    $description = $_POST['description'];
    $logo = $_FILES['logo'];
    // $currentlogo = $_POST['currentlogo'];
    //if user uploaded a new logo then upload it to the server
    if (!empty($logo['tmp_name'])) {
        //logo name will be userid.extention
        $logo_name = $_SESSION['user_id'] . '.' . pathinfo($logo['name'], PATHINFO_EXTENSION);
        $path = __DIR__ . '/../uploads/vendor/logo/' . $logo_name;
        move_uploaded_file($logo['tmp_name'], $path);
        $logo = $logo_name;
    } else {
        $logo = $vendor['logo_url'];
    }
    // echo $logo;
if($vendor){
    $query = "UPDATE vendors SET company_name = '$company_name', description = '$description', logo_url = '$logo' WHERE user_id = $vendor_id";   
}
    else {
        $query = "INSERT INTO vendors (user_id, company_name, description, logo_url) VALUES ($vendor_id, '$company_name', '$description', '$logo')";
    }

    $conn->query($query);
    if($conn->affected_rows > 0) {
        $_SESSION['message'] = 'Profile updated successfully';
        header('Location: vendor_profile.php'); 
        exit();
    }
    
}
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
        <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?= $vendor['user_id'] ?>">
            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="<?= $vendor['company_name']??'' ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"><?= $vendor['description']??'' ?></textarea>
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" class="form-control" id="logo" name="logo">
            </div>
            <div class="mb-3">
                <label for="currentlogo">Current Logo</label>
                <img src="<?= settings()['root'] . "uploads/vendor/logo/". $vendor['logo_url']??'' ?>" alt="Current Logo" width="100">
            </div>
            
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
