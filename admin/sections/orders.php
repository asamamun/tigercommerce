<?php
require '../../config/database.php'; // Include the database connection

// Handle Order Status Update
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status'];
    $id = $_GET['id'];

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect after status update
    header("Location: orders.php");
    exit();
}

// Fetch Orders
$orders = [];
$result = $conn->query("
    SELECT orders.*, users.username
    FROM orders
    JOIN users ON orders.user_id = users.id
");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<?php include('../partials/header.php'); ?>
<?php include('../partials/navbar.php'); ?>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <?php include('../partials/sidebar.php'); ?>
        </div>
        
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center my-4 order-header">
                <h2 class="order-title">Manage Orders</h2>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Total Amount</th>
                            <th>OrderStatus</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                                    <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
                                    <td>
                                        <span class="badge <?php echo getStatusBadgeClass($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                    <td>
                                        <?php if ($order['status'] == 'pending'): ?>
                                            <a href="orders.php?status=processing&id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">
                                                <i class="bi bi-gear"></i> Process
                                            </a>
                                        <?php elseif ($order['status'] == 'processing'): ?>
                                            <a href="orders.php?status=shipped&id=<?php echo $order['id']; ?>" class="btn btn-success btn-sm">
                                                <i class="bi bi-truck"></i> Ship
                                            </a>
                                        <?php endif; ?>
                                        <a href="orders.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm" title="View Order">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="invoice.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary btn-sm" target="_blank" title="View Invoice"><i class="bi bi-receipt"></i></a>
                                        <a href="career.php?id=<?php echo $order['id']; ?>" class="btn btn-warning btn-sm" title="Update Career"> <i class="bi bi-briefcase"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Order Items Table -->
            <?php if (isset($_GET['order_id'])): ?>
                <?php
                $orderId = $_GET['order_id'];
                //show order items with product name from products table join order_items and products table
                $orderItemsQuery = "SELECT * FROM order_items INNER JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?"; 

                
                $stmt = $conn->prepare($orderItemsQuery);
                $stmt->bind_param("i", $orderId);
                $stmt->execute();
                $orderItemsResult = $stmt->get_result();
                $orderItems = $orderItemsResult->fetch_all(MYSQLI_ASSOC);
                ?>
                <h3 class="my-4">Order Items</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td><?php echo $item['id']; ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantity'] * $item['price']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>

<?php
// Helper function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-warning text-dark';
        case 'processing':
            return 'bg-info text-dark';
        case 'shipped':
            return 'bg-primary text-light';
        case 'delivered':
            return 'bg-success text-light';
        case 'cancelled':
            return 'bg-danger text-light';
        default:
            return 'bg-secondary text-light';
    }
}
?>
