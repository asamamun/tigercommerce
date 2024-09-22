<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome to your Dashboard, [User Name]</h2>
            <p>This is your personal space to manage your profile, view your orders, and update your settings.</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text">Manage your account details and settings.</p>
                    <a href="/user/profile.php" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <p class="card-text">View your recent orders and order history.</p>
                    <a href="/user/orders.php" class="btn btn-primary">View Orders</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Settings</h5>
                    <p class="card-text">Update your preferences and account settings.</p>
                    <a href="/user/settings.php" class="btn btn-primary">Update Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
