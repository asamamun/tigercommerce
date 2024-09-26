<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

?>
<?php require "partials/header.php" ?>

</head>
<body>
<?php require "partials/navbar.php" ?>
    <div class="container main-content">
        <h2 class="text-center mb-4">Cart Details</h2>
        <!-- cart details -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
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
                    <!-- Cart items will be inserted here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                        <td id="grandTotal"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- cart details end  -->
        
        <!-- place order start -->
        <div class="d-flex justify-content-center mt-4">
            <a class="btn btn-lg btn-primary" id="placeOrderBtn" href="place-order.php">Place Order</a>
        </div>
        <!-- place order end -->
    </div>

    <?php require "partials/footer.php" ?>
    <script src="assets/js/cart-page.js"></script>
</body>
</html>
