<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/database.php";

// Fetch vendor products (hardcoded vendor ID for simplicity)
$vendor_id = $_SESSION['vendor_id'];
if(!isset($vendor_id)) {
    echo "Create vendor profile first";
    exit();
}
// echo $vendor_id;
$query = "SELECT * FROM products WHERE vendor_id = $vendor_id";
// echo $query;
$result = $conn->query($query);
$products = [];
if($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
    // var_dump($products);
    // exit;
}
?>
<!-- header -->
<?php require __DIR__ . "/partials/header.php"; ?>
</head>

<body>
    <div class="wrapper">
<!-- sidebar -->
        <?php require __DIR__ . "/partials/leftbar.php"; ?>
        <div class="main">
<?php require __DIR__ . "/partials/navbar.php"; ?>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h4>Admin Dashboard</h4>
                    </div>
                    <div class="row">
<!-- my content -->
<h2>Products</h2>
        <a href="products-add.php" class="btn btn-primary mb-3">Add Product</a>
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
                <?php if ($products): ?>
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
                <?php else: ?>
                <tr>
                    <td colspan="6">No products found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

                    </div>
                    <!-- Table Element -->

                </div>
            </main>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
<!-- footer -->
<?php require __DIR__ . "/partials/footer.php"; ?>


        
    