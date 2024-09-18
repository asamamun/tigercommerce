<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/database.php";

// Fetch vendor products (hardcoded vendor ID for simplicity)
$vendor_id = $_SESSION['vendor_id'];
$query = "SELECT * FROM products WHERE vendor_id = $vendor_id";
// echo $query;
$result = $conn->query($query);
if($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
    // var_dump($products);
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Products</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['price'] ?></td>
                    <td><?= $product['stock_quantity'] ?></td>
                    <td><?= $product['status'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-primary">Edit</a>
                        <a onclick="return confirm('Are you sure you want to delete this product?')" href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
