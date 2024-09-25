<?php require __DIR__ . '../../../vendor/autoload.php'; ?>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
    <div class="container-fluid">
        <!-- Website Name -->
        <a class="navbar-brand" href="<?= settings()['root']; ?>index.php">
            <i class="bi bi-shop-window"></i> TigerCommerce
            <!-- Sidebar Toggle Button -->
            <button class="btn" id="toggleSidebarBtn" style="background-color: transparent; border: none;">
                <i class="bi bi-list"></i>
            </button>
        </a>

        <!-- Search Bar and Logout -->
        <div class="d-flex align-items-center ml-auto">
            <form class="d-flex me-3">
                <input class="form-control rounded-pill" type="search" placeholder="Search" aria-label="Search">
            </form>
            <a href="<?= settings()['root']; ?>logout.php" class="btn btn-outline-light rounded-pill">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>
<script>
    document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        if (sidebar.style.display === 'none') {
            sidebar.style.display = 'flex';
        } else {
            sidebar.style.display = 'none';
        }
    });
</script>