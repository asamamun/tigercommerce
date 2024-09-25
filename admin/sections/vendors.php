<?php
require '../../config/database.php'; // Include the database connection

// Handle Vendor Status Update
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status'];
    $id = $_GET['id'];

    // Update vendor status
    $stmt = $conn->prepare("UPDATE vendors SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect after status update
    header("Location: vendors.php");
    exit();
}

// Fetch Vendors
$vendors = [];
$result = $conn->query("SELECT vendors.*, users.username FROM vendors JOIN users ON vendors.user_id = users.id");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vendors[] = $row;
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
            <div class="d-flex justify-content-between align-items-center my-4 vendor-header">
                <h2 class="vendor-title">Manage Vendors</h2>
            </div>

            <!-- Vendors Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Username</th>
                            <th>Description</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($vendors)): ?>
                            <?php foreach ($vendors as $vendor): ?>
                                <tr>
                                    <td><?php echo $vendor['id']; ?></td>
                                    <td><?php echo htmlspecialchars($vendor['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($vendor['username']); ?></td>
                                    <td><?php echo htmlspecialchars($vendor['description']); ?></td>
                                    <td>
                                        <?php if ($vendor['logo_url']): ?>
                                            <img src="<?= settings()['root'] ."/uploads/vendor/logo/". htmlspecialchars($vendor['logo_url']); ?>" alt="Logo" width="100" height="100">
                                        <?php else: ?>
                                            No Logo
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $vendor['status'] == 'active' ? 'bg-success' : ($vendor['status'] == 'inactive' ? 'bg-warning' : 'bg-danger'); ?>">
                                            <?php echo ucfirst($vendor['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($vendor['status'] == 'inactive'): ?>
                                            <a href="vendors.php?status=active&id=<?php echo $vendor['id']; ?>" class="btn btn-success btn-sm">
                                                <i class="bi bi-check"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="vendors.php?status=inactive&id=<?php echo $vendor['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pause"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="vendors.php?status=suspended&id=<?php echo $vendor['id']; ?>" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No vendors found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>
