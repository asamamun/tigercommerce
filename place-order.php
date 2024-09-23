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
            <h3>Grand Total: <span id="grandTotal"></span></h3>
            <!-- cart details end  -->
             <!-- place order start -->
              <div class="flex justify-content-center">
                <?php
                    if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] != 'customer'){
                        echo '<a class="btn btn-lg btn-primary" href="login.php">Login </a> or <a class="btn btn-lg btn-primary" href="register.php">Register</a> to place order';
                    }
                    else{
                        ?>
                <a class="btn btn-lg btn-primary" id="placeOrderBtn" href="place-order.php">Place Order</a>
                        <?php
                    }
                ?>
                

              </div>
              
             <!-- place order end -->

            

        </div>
    </div>

    <?php require "partials/footer.php" ?> 
    <script>

        const grandTotal = cart.getTotalPrice();

        document.getElementById('grandTotal').innerHTML = grandTotal;
    </script>
    </body>
</html>   