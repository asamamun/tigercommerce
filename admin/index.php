<?php
require '../config/database.php'; // Include the database connection

// Fetch Statistics
$stats = [
    'total_users' => 0,
    'total_vendors' => 0,
    'total_products' => 0,
    'total_orders' => 0,
];

// Get Total Users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
if ($result->num_rows > 0) {
    $stats['total_users'] = $result->fetch_assoc()['total_users'];
}

// Get Total Vendors
$result = $conn->query("SELECT COUNT(*) AS total_vendors FROM vendors");
if ($result->num_rows > 0) {
    $stats['total_vendors'] = $result->fetch_assoc()['total_vendors'];
}

// Get Total Products
$result = $conn->query("SELECT COUNT(*) AS total_products FROM products");
if ($result->num_rows > 0) {
    $stats['total_products'] = $result->fetch_assoc()['total_products'];
}

// Get Total Orders
$result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
if ($result->num_rows > 0) {
    $stats['total_orders'] = $result->fetch_assoc()['total_orders'];
}
?>

<?php include('partials/header.php'); ?>
<?php include('partials/navbar.php'); ?>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-lg-3 col-md-4 sidebar-section">
            <?php include('partials/sidebar.php'); ?>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center my-4 dashboard-header">
                <h2 class="dashboard-title">Admin Dashboard</h2>
            </div>

            <div class="row">
                <!-- Total Users Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card custom-card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <a href="<?= settings()['adminpage']; ?>sections/users.php" class="stretched-link"></a>
                            <p class="card-text display-6"><?php echo $stats['total_users']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Vendors Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card custom-card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Total Vendors</h5>
                            <a href="<?= settings()['adminpage']; ?>sections/vendors.php" class="stretched-link"></a>
                            <p class="card-text display-6"><?php echo $stats['total_vendors']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Products Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card custom-card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <a href="<?= settings()['adminpage']; ?>sections/products.php" class="stretched-link"></a>
                            <p class="card-text display-6"><?php echo $stats['total_products']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card custom-card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <a href="<?= settings()['adminpage']; ?>sections/orders.php" class="stretched-link"></a>
                            <p class="card-text display-6"><?php echo $stats['total_orders']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>
