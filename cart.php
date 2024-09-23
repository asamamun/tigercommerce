<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

?>
<?php require "partials/header.php" ?>

</head>
<body>
    <div class="container">
        <?php require "partials/navbar.php" ?>
        <hr>
        <div class="container mt-5">
            <h2 class="text-center">Cart Details</h2>
            <!-- cart details -->
            <table class="table">
                <thead>
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
                        <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                        <td id="grandTotal"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <!-- cart details end  -->
             <!-- place order start -->
              <div class="flex justify-content-center">
                <a class="btn btn-lg btn-primary" id="placeOrderBtn" href="place-order.php">Place Order</a>

              </div>
              
             <!-- place order end -->

            

        </div>
    </div>

    <?php require "partials/footer.php" ?>
    <script src="assets/js/cart-page.js"></script>
    </body>
</html>
      