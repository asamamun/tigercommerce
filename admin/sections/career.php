<?php
session_start();
require '../../config/database.php'; // Include the database connection
if($_SESSION['role'] != 'admin'){
    echo "Only admins can see this page.";
    exit();
}
//update order table for carrier
if (isset($_POST['update'])) {
    $orderid = $_POST['order_id'];
    $carrier = $_POST['carrier'];
    $shipment_date = $_POST['shipment_date'];
    $tracking_number = $_POST['tracking_number'];
    $shipping_status = $_POST['shipping_status'];
    $stmt = $conn->prepare("UPDATE orders SET carrier = ?, shipment_date = ?, tracking_number = ?, shipping_status = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $carrier, $shipment_date, $tracking_number, $shipping_status, $orderid);
    $stmt->execute();
    $stmt->close();
    header("Location: career.php?id=$orderid");
    exit();
}

//get orders records
$orderid = $_GET['id'];
$query = "SELECT * FROM orders WHERE id = ? limit 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderid);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_assoc();

?>

<?php include('../partials/header.php'); ?>
<?php include('../partials/navbar.php'); ?>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <?php include('../partials/sidebar.php'); ?>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center my-4 category-header">
                <h2 class="category-title">Manage Career Info</h2>                
            </div>
            <form action="" method="post">
                <input type="hidden" name="order_id" value="<?php echo $orderid; ?>">
                <!-- carrier
shipment_date
tracking_number
shipping_status
created_at
updated_at
 -->
                <div class="mb-3">
                    <label for="carrier" class="form-label">Carrier</label>
                    <input type="text" class="form-control" name="carrier" id="carrier" value="<?= $orders['carrier'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="shipment_date" class="form-label">Shipment Date</label>
                    <input type="date" class="form-control" name="shipment_date" id="shipment_date" value="<?= $orders['shipment_date'] ?>">
                </div>
                <div class="mb-3">
                    <label for="tracking_number" class="form-label">Tracking Number</label>
                    <input type="text" class="form-control" name="tracking_number" id="tracking_number" value="<?= $orders['tracking_number'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="shipping_status" class="form-label">Shipping Status</label>
                    <select name="shipping_status" id="" class="form-select">
                        <option value="pending" <?= $orders['shipping_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="shipped" <?= $orders['shipping_status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="delivered" <?= $orders['shipping_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    </select>
                </div>
                <div class="mb-3">
                <button type="submit" name="update" class="btn btn-primary">Submit</button>
                </div>
            </form>






        </div>
    </div>
</div>



<script>

</script>

<?php include('../partials/footer.php'); ?>