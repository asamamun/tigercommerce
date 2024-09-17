<?php
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

// Fetch Products
$products = [];
$result = $conn->query("
    SELECT products.*, vendors.company_name, categories.name as category_name
    FROM products
    JOIN vendors ON products.vendor_id = vendors.id
    JOIN categories ON products.category_id = categories.id
");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<?php include('../partials/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include('../partials/sidebar.php'); ?>
        </div>
        <div class="col-9">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h2>Products</h2>
                <a href="add_product.php" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Add Product
                </a>
            </div>

            <!-- Products Table -->
            <table class="table table-bordered table-striped">
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
                                    <?php if ($product['image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" width="100" height="100">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $product['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($product['status'] == 'inactive'): ?>
                                        <a href="products.php?status=active&id=<?php echo $product['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-check"></i> Activate
                                        </a>
                                    <?php else: ?>
                                        <a href="products.php?status=inactive&id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pause"></i> Deactivate
                                        </a>
                                    <?php endif; ?>
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-info btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
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

<?php include('../partials/footer.php'); ?>
