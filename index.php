<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/partials/commonfx.php';
if(isset($_GET['category'])){
    $cat_id = $_GET['category'];
    //show product with vendor and category name
    $query = "SELECT p.*, i.url
    FROM products p
    LEFT JOIN (
        SELECT product_id, MIN(id) as min_id
        FROM images
        GROUP BY product_id
    ) first_img ON p.id = first_img.product_id
    LEFT JOIN images i ON first_img.min_id = i.id
    WHERE p.status = 'active' AND p.category_id = $cat_id";
}
else{
    $query = "SELECT p.*, i.url
                        FROM products p
                        LEFT JOIN (
                            SELECT product_id, MIN(id) as min_id
                            FROM images
                            GROUP BY product_id
                        ) first_img ON p.id = first_img.product_id
                        LEFT JOIN images i ON first_img.min_id = i.id
                        WHERE p.status = 'active'";
}

$productresult = $conn->query($query);
// var_dump($result);
?>
<?php require "partials/header.php" ?>
</head>

<body>
    <?php require "partials/navbar.php" ?>
    <div class="container main-content">
        <?php require "partials/carousel.php" ?>
        <div class="row mt-4">
            <div class="col-md-2 mb-4">
                <?php include('partials/sidebar.php'); ?>
            </div>
            <div class="col-md-10">
                <!-- show products and images join -->
                <div class="row g-4">
                    <h1>hhh</h1>
                    <?php
                    //select images join products
                    
                    
                    if ($productresult->num_rows > 0) {
                        // echo "<h1>Products".$productresult->num_rows."</h1>" ;
                        while ($row = $productresult->fetch_assoc()) {
                            // var_dump($row);
                            // echo "<h3>test</h3>";
                            echo generateProductCard($row);
                        }
                    }
                    ?>
                    <h1>iii</h1>
                </div>
            </div>
        </div>
    </div>

    <?php require "partials/footer.php" ?>
</body>

</html>