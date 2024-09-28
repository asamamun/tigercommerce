<?php
//autoload
require '../../vendor/autoload.php';
require '../../config/database.php'; // Include the database connection

// Handle Product Status Update
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status'];
    $id = $_GET['id'];

    // Update product status
    $stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect after status update
    header("Location: products.php");
    exit();
}

// Handle Product Deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Redirect after deletion
    header("Location: products.php");
    exit();
}

// Fetch Products
$products = [];
$result = $conn->query("
    SELECT products.*, vendors.company_name, categories.name as category_name, min(images.url) as image
    FROM products
    JOIN vendors ON products.vendor_id = vendors.id
    LEFT JOIN images ON products.id = images.product_id
    JOIN categories ON products.category_id = categories.id
    GROUP BY 
    products.id, 
    vendors.company_name, 
    categories.name;
");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
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
            <div class="d-flex justify-content-between align-items-center my-4 product-header">
                <h2 class="product-title">Manage Products</h2>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Vendor</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                                    <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['company_name']); ?></td>
                                    <td>
                                        <?php if ($product['image']): ?>
                                            <img src="<?= settings()['root']  ?>/uploads/vendor/products/<?= $product['vendor_id']; ?>/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="100" height="100">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $product['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>" title="status">
                                            <?php echo ucfirst($product['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around gap-2">
                                            <?php if ($product['status'] == 'inactive'): ?>
                                                <a href="products.php?status=active&id=<?php echo $product['id']; ?>" class="btn btn-success btn-sm" title="set to active">
                                                    <i class="bi bi-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="products.php?status=inactive&id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm" title="set to inactive">
                                                    <i class="bi bi-pause"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="products.php?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>