<?php
session_start();
// echo $_SESSION['role'];
// exit();
require '../../config/database.php'; // Include the database connection
require '../../vendor/autoload.php'; // Include the database connection
if($_SESSION['role'] != 'admin'){
    echo "Only admins can see invoices.";
    exit();
}


// Fetch order details
$order_id = $_GET['id']; // Assuming you pass the order_id via GET

$order_query = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Fetch order items
$order_items_query = "SELECT oi.*, p.name as product_name FROM order_items oi 
                      JOIN products p ON oi.product_id = p.id WHERE order_id = ?";
$stmt = $conn->prepare($order_items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $order['id']; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body onload="window.print();">

<div class="container my-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Invoice #<?php echo $order['id']; ?></h4>
        </div>
        <div class="card-body">
            <!-- Company Info -->
            <div class="row">
                <div class="col-md-4">
                    <h5><?= settings()['companyname']; ?></h5>
                    
                    <p>
                        123 Business Street<br>
                        City, Country<br>
                        Phone: 123-456-7890<br>
                        Email: info@company.com
                    </p>
                </div>
                <div class="col-md-4">
                <img src="<?= settings()['logo']; ?>" alt="" width="150">
                </div>
                <!-- Customer Info -->
                <div class="col-md-4 text-end">
                    <h5>Billing Address</h5>
                    <p>
                        <?php echo $order['address']; ?><br>
                        Phone: <?php echo $order['phone']; ?>
                    </p>
                    <p>
                        <strong>Order Date:</strong> <?php echo date('d M, Y', strtotime($order['created_at'])); ?><br>
                        <strong>Status:</strong> <?php echo ucfirst($order['status']); ?>
                    </p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        $grand_total = 0;
                        while ($item = $order_items_result->fetch_assoc()) {
                            $total_price = $item['quantity'] * $item['price'];
                            $grand_total += $total_price;
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo $item['product_name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo number_format($total_price, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total Amount:</th>
                            <th><?php echo number_format($grand_total, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Shipping & Payment Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Shipping Info</h5>
                    <p>
                        <strong>Carrier:</strong> <?php echo $order['carrier']; ?><br>
                        <strong>Shipment Date:</strong> <?php echo date('d M, Y', strtotime($order['shipment_date'])); ?><br>
                        <strong>Tracking Number:</strong> <?php echo $order['tracking_number']; ?><br>
                        <strong>Shipping Status:</strong> <?php echo ucfirst($order['shipping_status']); ?>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Payment Info</h5>
                    <p>
                        <strong>Transaction:</strong> <?php echo $order['transaction']; ?><br>
                        <strong>Transaction ID:</strong> <?php echo $order['trxid']; ?><br>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-center">
            <p class="mb-0">Thank you for your business!</p>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
