<?php
session_start();
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config/database.php';
require __DIR__.'/partials/commonfx.php';
?>
<?php require "partials/header.php" ?>
</head>
<body>
    <div class="container-fluid">
    <?php require "partials/navbar.php" ?>
    <hr>

    <?php require "partials/carousel.php" ?>
    <div class="row">
        <div class="col-3">
            <?php include('partials/sidebar.php'); ?>
        </div>
        <div class="col-9">
        <!-- show products and images join -->
         <div class="row">
         <?php
         //select iamges join products
            // $query = "SELECT * FROM products JOIN images ON products.id = images.product_id where status = 'active'";
            $query = "SELECT p.*, i.url
    FROM products p
    LEFT JOIN (
        SELECT product_id, MIN(id) as min_id
        FROM images
        GROUP BY product_id
    ) first_img ON p.id = first_img.product_id
    LEFT JOIN images i ON first_img.min_id = i.id
    WHERE p.status = 'active'";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo generateProductCard($row);
                    
                }
            }

         ?>
        </div>
        </div>
    </div>
    </div>

    <?php require "partials/footer.php" ?>
    </body>
</html>