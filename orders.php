<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if($_SESSION['role'] != 'customer'){
   echo "Only customers can see his orders."; 
   exit();
}

$orderQuery = "SELECT * FROM orders WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($orderQuery);



?>
<?php require "partials/header.php" ?>

</head>
<body>
<?php require "partials/navbar.php" ?>
    <div class="container main-content">
        <h2 class="text-center mb-4">My Orders</h2>
        <!-- cart details -->
        <div class="table-responsive">
            <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Trx Type</th>
                <th>Trx ID</th>
                <th>Career</th>
                <th>Shipment Date</th>
                <th>Tracking Number</th>
                <th>Shipping Status</th>
                <th>Order Date</th>
                <th>Products Ordered</th>                
                </tr>
            </thead>
            <tbody id="orderItems">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['total_amount'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['transaction'] . "</td>";
                        echo "<td>" . $row['trxid'] . "</td>";
                        echo "<td>" . $row['carrier'] . "</td>";
                        echo "<td>" . $row['shipment_date'] . "</td>";
                        echo "<td>" . $row['tracking_number'] . "</td>";
                        echo "<td>" . $row['shipping_status'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>";
                        //show product name from inner join  order_item(product_id) and product(id) table
                        $orderItemsQuery = "SELECT `quantity`, `name` FROM order_items INNER JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = " . $row['id'];

                        $orderItemsResult = $conn->query($orderItemsQuery);
                        if ($orderItemsResult->num_rows > 0) {
                            while ($orderItem = $orderItemsResult->fetch_assoc()) {
                                echo $orderItem['quantity'] . " x " . $orderItem['name'] . "<br>";
                            }
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
            <tfoot>
                
            </tfoot>
            </table>
        </div>
        <!-- cart details end  -->
        
        
    </div>

    <?php require "partials/footer.php" ?>
</body>
</html>
