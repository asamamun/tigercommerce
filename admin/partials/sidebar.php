<!-- /admin/partials/sidebar.php -->
<?php require __DIR__. '../../../vendor/autoload.php'; ?>
<nav class="navbar navbar-dark bg-dark flex-column sidebar" style="min-height: 100vh;">
    <a class="navbar-brand mb-3" href="<?= settings()['adminpage']; ?>index.php">
        <i class="bi bi-speedometer2"></i> Admin Panel
    </a>
    <ul class="navbar-nav flex-column w-100">
        <li class="nav-item">
            <a class="nav-link text-light" href="<?= settings()['adminpage']; ?>sections/category.php">
                <i class="bi bi-tags"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="<?= settings()['adminpage']; ?>sections/users.php">
                <i class="bi bi-person"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="<?= settings()['adminpage']; ?>sections/vendors.php">
                <i class="bi bi-shop"></i> Vendors
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="<?= settings()['adminpage']; ?>sections/products.php">
                <i class="bi bi-box"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-light" href="<?= settings()['adminpage']; ?>sections/orders.php">
                <i class="bi bi-cart"></i> Orders
            </a>
        </li>
    </ul>
</nav>






