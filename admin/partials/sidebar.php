<!-- /admin/partials/sidebar.php -->
<?php require __DIR__. '../../../vendor/autoload.php'; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-dark flex-column">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?= settings()['adminpage']; ?>sections/category.php"><i class="bi bi-tags"></i> Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= settings()['adminpage']; ?>sections/users.php"><i class="bi bi-person"></i> Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= settings()['adminpage']; ?>sections/vendors.php"><i class="bi bi-shop"></i> Vendors</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= settings()['adminpage']; ?>sections/products.php"><i class="bi bi-box"></i> Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= settings()['adminpage']; ?>sections/orders.php"><i class="bi bi-cart"></i> Orders</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo settings()['root']; ?>logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </ul>
    </div>
</nav>
