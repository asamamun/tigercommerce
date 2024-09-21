<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Vendor Dashboard</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="msapplication-TileColor" content="#dd3d31">
        <meta name="theme-color" content="#dd3d31">
        <link rel="icon" type="image/png" href="favicon.png">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Preload icons and styles -->
        <link rel="apple-touch-icon" sizes="180x180" href="https://assets.startbootstrap.com/img/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://assets.startbootstrap.com/img/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://assets.startbootstrap.com/img/icons/favicon-16x16.png">
        <link rel="mask-icon" href="https://assets.startbootstrap.com/img/icons/safari-pinned-tab.svg" color="#dd3d31">
        <link rel="manifest" href="manifest.webmanifest">
        <meta name="description" content="Start Bootstrap develops free to download, open source Bootstrap 5 themes, templates, and snippets.">
        <meta name="og:title" content="Free Bootstrap Themes, Templates, Snippets, and Guides">
        <meta name="og:site_name" content="Start Bootstrap">
        <meta name="og:url" content="https://startbootstrap.com">
        <meta name="twitter:card" content="summary_large_image">
        
        <script async="" src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5287323383309901" crossorigin="anonymous"></script>
        
        <!-- Custom Styles -->
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f8f9fa;
            }

            /* Sidebar */
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                height: 100%;
                background-color: #343a40;
                padding-top: 20px;
            }

            #sidebar .nav-link {
                color: #fff;
                margin: 10px;
                font-size: 16px;
            }

            #sidebar .nav-link:hover {
                background-color: #495057;
                border-radius: 5px;
            }

            #content {
                margin-left: 250px;
                padding: 20px;
            }

            /* Navbar */
            .navbar-brand {
                color: #dc3545 !important;
                font-weight: bold;
            }

            .navbar-light .navbar-toggler-icon {
                background-color: #6c757d;
            }

            .nav-item .nav-link {
                color: #6c757d;
            }

            .nav-item .nav-link:hover {
                color: #495057;
            }

            /* Table */
            table thead th {
                background-color: #f8f9fa;
                font-weight: bold;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="container-fluid">
                <a class="navbar-brand text-center d-block mb-4" href="#">Vendor Dashboard</a>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="vendor_profile.php">Vendor Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                         <li class="nav-item">
                                <a class="nav-link" href="#">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid mt-4">
                <h2>Welcome to Vendor Dashboard</h2>
                <p>Use the sidebar navigation to manage your shop, view orders, and update your products.</p>

                <!-- Sample Dashboard Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4 bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Orders</h5>
                                <p class="card-text">Manage and track all your orders.</p>
                                <a href="orders.php" class="btn btn-primary">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 bg-success">
                        <div class="card mb-4 bg-warning">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <p class="card-text">Manage and update your product listings.</p>
                                <a href="products.php" class="btn btn-primary">View Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 bg-info">
                        <div class="card mb-4 bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title">Profile</h5>
                                <p class="card-text">Update your vendor profile and details.</p>
                                <a href="vendor_profile.php" class="btn btn-primary">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

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
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
