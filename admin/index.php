<?php
session_start();
require '../config/database.php';
// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Determine which section to include based on the query parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Your CSS file -->
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index.php?page=dashboard">Dashboard</a></li>
            <li><a href="category-create.php">Categories</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="vendors.php">Vendors</a></li>
            <li><a href="products">Products</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content" id="main-content">
        <?php
        switch ($page) {
            case 'dashboard':
                include 'sections/dashboard.php';
                break;
            case 'manage_users':
                include 'sections/manage_users.php';
                break;
            case 'manage_vendors':
                include 'sections/manage_vendors.php';
                break;
            case 'manage_products':
                include 'sections/manage_products.php';
                break;
            case 'manage_orders':
                include 'sections/manage_orders.php';
                break;
            default:
                echo "<p>Content not found.</p>";
                break;
        }
        ?>
    </div>
</body>
</html>
