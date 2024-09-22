<!-- header -->
<?php require __DIR__ . "/partials/header.php"; ?>
</head>

<body>
    <div class="wrapper">
<!-- sidebar -->
        <?php require __DIR__ . "/partials/leftbar.php"; ?>
        <div class="main">
<?php require __DIR__ . "/partials/navbar.php"; ?>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h4>Admin Dashboard</h4>
                    </div>
                    <div class="row">
<!-- my content -->
<!-- Bootstrap Table -->
<div class="row">
                    <div class="col-md-12">
                        <h3 class="mb-3">Recent Orders</h3>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>ORD1234</td>
                                    <td>John Doe</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>2024-09-21</td>
                                    <td>$99.99</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <td>ORD1235</td>
                                    <td>Jane Smith</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>2024-09-22</td>
                                    <td>$149.99</td>
                                </tr>
                                <tr>
                                    <th>3</th>
                                    <td>ORD1236</td>
                                    <td>Michael Johnson</td>
                                    <td><span class="badge bg-danger">Canceled</span></td>
                                    <td>2024-09-23</td>
                                    <td>$79.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                    </div>
                    <!-- Table Element -->

                </div>
            </main>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
<!-- footer -->
<?php require __DIR__ . "/partials/footer.php"; ?>

                
            