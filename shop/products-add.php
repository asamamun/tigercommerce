<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';
if (!isset($_SESSION['vendor_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $vendor_id = $_SESSION['vendor_id'];
    $category_id = intval($_POST['category_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $status = $conn->real_escape_string($_POST['status']);

    // Insert product into the database
    $sql = "INSERT INTO products (vendor_id, category_id, name, description, price, stock_quantity, status, created_at, updated_at)
            VALUES ('$vendor_id', '$category_id', '$name', '$description', '$price', '$stock_quantity', '$status', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        $product_id = $conn->insert_id;  // Get the ID of the newly inserted product
        
        // Check if images were uploaded
        if (!empty($_FILES['images']['name'][0])) {
            $total_files = count($_FILES['images']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                //max 5 iamges
                if($i>=10) break;
                $image_name = $_FILES['images']['name'][$i];
                $image_tmp = $_FILES['images']['tmp_name'][$i];
                $image_size = $_FILES['images']['size'][$i];
                $image_error = $_FILES['images']['error'][$i];

                // Validate image size and type
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif','webp'];
                $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                if (in_array($image_extension, $allowed_extensions) && $image_size < 2000000) {  // 2MB limit
                    if ($image_error === 0) {
                        // Define image path (you can change the path as per your setup)
                        $image_new_name = uniqid('', true) . "." . $image_extension;
                        $uploadpath =  __DIR__ . "/../uploads/vendor/products/" . $vendor_id . "/";
                        if(!is_dir($uploadpath)) {
                            mkdir($uploadpath, 0777, true);
                        }
                        
                        $image_upload_path = $uploadpath . $image_new_name;

                        // Move uploaded image to the destination
                        if (move_uploaded_file($image_tmp, $image_upload_path)) {
                            // Insert image into the database
                            $sql_image = "INSERT INTO images (product_id, url, created_at, updated_at)
                                          VALUES ('$product_id', '$image_new_name', NOW(), NOW())";
                            $conn->query($sql_image);
                        }
                    }
                }
            }
        }
        
        $message =  "Product added successfully.";
    } else {
        $message =  "Error: " . $sql . "<br>" . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Product</h1>
        <?php
        if (isset($message)) {
            echo '<div class="alert alert-success">' . $message . '</div>';
        }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
                       
            <!-- Category ID -->
            <div class="mb-3">
                <label for="category_id" class="form-label">Category ID</label>
                <?php
                $query = "SELECT * FROM categories";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    echo '<select class="form-select" id="category_id" name="category_id" required>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    echo '</select>';
                }
                ?>
            </div>

            <!-- Product Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>

            <!-- Stock Quantity -->
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Images (Multiple) -->
            <div class="mb-3">
                <label for="images" class="form-label">Product Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
