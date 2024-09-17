<?php
require '../../config/database.php'; // Include the database connection

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

// Fetch Order Items
$orderItems = [];
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    $stmt = $conn->prepare("
        SELECT order_items.*, products.name as product_name, products.price as product_price
        FROM order_items
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $orderItems[] = $row;
    }
    $stmt->close();
}
?>

<?php include('../partials/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include('../partials/sidebar.php'); ?>
        </div>
        <div class="col-9">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h2>Orders</h2>
            </div>

            <!-- Orders Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Total Amount</th>
                        <th>Status</th>
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
                                    <a href="orders.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye"></i> View Items
                                    </a>
                                    <?php if ($order['status'] != 'delivered' && $order['status'] != 'cancelled'): ?>
                                        <a href="orders.php?status=shipped&id=<?php echo $order['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-truck"></i> Mark as Shipped
                                        </a>
                                    <?php endif; ?>
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

            <?php if (isset($_GET['order_id']) && !empty($orderItems)): ?>
                <h3 class="my-4">Order Items</h3>
                <table class="table table-bordered table-striped">
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
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($item['product_price']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity'] * $item['product_price']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
