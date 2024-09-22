        <?php
        require __DIR__ . "/../../vendor/autoload.php";
        ?>
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="container-fluid">
                <a class="navbar-brand text-center d-block mb-4" href="<?= settings()['vendorpage']; ?>">Vendor Dashboard</a>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= settings()['vendorpage']; ?>vendor_profile.php">Vendor Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= settings()['vendorpage']; ?>orders.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= settings()['vendorpage']; ?>products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= settings()['vendorpage']; ?>settings.php">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= settings()['root']; ?>logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>