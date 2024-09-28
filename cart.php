<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Check if product already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Handle update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Handle remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

?>
<?php require "partials/header.php" ?>

</head>
<body>
<?php require "partials/navbar.php" ?>
    <div class="container main-content">
        <h2 class="text-center mb-4">Cart Details</h2>
        <!-- cart details -->
        <div class="table-responsive">
            <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                <th>Product ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody id="cartItems">
                <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
                <tr>
                <td><?php echo $product_id; ?></td>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo number_format($product['price'], 2); ?></td>
                <td>
                    <form method="post" action="cart.php" class="d-flex align-items-center">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" min="1" class="form-control">
                    <button type="submit" name="update_quantity" class="btn btn-sm btn-primary ms-2">Update</button>
                    </form>
                </td>
                <td><?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
                <td>
                    <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">Remove</button>
                    </form>
                </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                <td id="grandTotal">
                    <?php
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $product) {
                    $grand_total += $product['price'] * $product['quantity'];
                    }
                    echo number_format($grand_total, 2);
                    ?>
                </td>
                <td></td>
                </tr>
            </tfoot>
            </table>
        </div>
        <!-- cart details end  -->
        
        <!-- place order start -->
         <?php if($_SESSION['role'] == "customer") { ?>
        <div class="d-flex justify-content-center mt-4">
            <a class="btn btn-lg btn-primary" id="placeOrderBtn" href="place-order.php">Place Order</a>
        </div>
        <?php } else { echo "Only customers can place orders."; } ?>

        <!-- place order end -->
    </div>

    <?php require "partials/footer.php" ?>
    <script src="assets/js/cart-page.js"></script>
</body>
</html>
