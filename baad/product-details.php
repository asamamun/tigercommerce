<?php
session_start();
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config/database.php';
require __DIR__.'/partials/commonfx.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    //show product with vendor and category name
    $query = "SELECT p.*, c.name as category_name, v.company_name as vendor_name FROM products p JOIN categories c ON p.category_id = c.id JOIN vendors v ON p.vendor_id = v.id WHERE p.id = $id";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();

    if (!$product) {
        header('Location: index.php');
    }
    //images
    $query = "SELECT url FROM images WHERE product_id = $id";
    $result = $conn->query($query);
    $images = $result->fetch_all(MYSQLI_ASSOC);
}
else {
    header('Location: index.php');
}

?>
<?php require "partials/header.php" ?>
<style>
    .text-bold {
            font-weight: 800;
        }

        text-color {
            color: #0093c4;
        }

        /* Main image - left */
        .main-img img {
            width: 100%;
        }

        /* Preview images */
        .previews img {
            width: 100%;
            height: 140px;
        }

        .main-description .category {
            text-transform: uppercase;
            color: #0093c4;
        }

        .main-description .product-title {
            font-size: 2.5rem;
        }

        .old-price-discount {
            font-weight: 600;
        }

        .new-price {
            font-size: 2rem;
        }

        .details-title {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 1.2rem;
            color: #757575;
        }

        .buttons .block {
            margin-right: 5px;
        }

        .quantity input {
            border-radius: 0;
            height: 40px;

        }


        .custom-btn {
            text-transform: capitalize;
            background-color: #0093c4;
            color: white;
            width: 150px;
            height: 40px;
            border-radius: 0;
        }

        .custom-btn:hover {
            background-color: #0093c4 !important;
            font-size: 18px;
            color: white !important;
        }

        .similar-product img {
            height: 400px;
        }

        .similar-product {
            text-align: left;
        }

        .similar-product .title {
            margin: 17px 0px 4px 0px;
        }

        .similar-product .price {
            font-weight: bold;
        }

        .questions .icon i {
            font-size: 2rem;
        }

        .questions-icon {
            font-size: 2rem;
            color: #0093c4;
        }


        /* Small devices (landscape phones, less than 768px) */
        @media (max-width: 767.98px) {

            /* Make preview images responsive  */
            .previews img {
                width: 100%;
                height: auto;
            }

        }
</style>
</head>

<body>
    <div class="container-fluid">
    <?php require "partials/navbar.php" ?>
    <div class="row">
        <div class="col-3">
            <?php include('partials/sidebar.php'); ?>
        </div>
        <div class="col-9">
        <!-- show products and images join -->
         
        <!-- show product start -->
        <div class="container my-5">
        <div class="row">
            <div class="col-md-5">
                <div class="main-img">
                    <?php
                    //if no image then show image_not_found.png
                    if (count($images) == 0) {
                        ?>
                        <img class="img-fluid" src="uploads/vendor/products/image_not_found.png" alt="ProductS">
                    <?php }
                    else{
                        ?>
<img class="img-fluid" src="uploads/vendor/products/<?= $product['vendor_id'] ?>/<?= $images[0]['url'] ?>" alt="ProductS" id="product-image">
                        <?php

                    }
                    ?>
                    
                    <div class="row my-3 previews">
                        <?php
                        foreach ($images as $k=>$image) {
                           // if($k == 0) continue;
                            echo '<div class="col-md-3">
                                    <img class="w-100" src="uploads/vendor/products/'.$product['vendor_id'].'/'.$image['url'].'" alt="Preview" onclick="changeImage(\''.$image['url'].'\' , \''.$product['vendor_id'].'\')">
                                </div>';
                        }
                        ?>
                        <!-- <div class="col-md-3">
                            <img class="w-100" src="https://cdn.pixabay.com/photo/2015/07/24/18/40/model-858754_960_720.jpg" alt="Sale">
                        </div>
                        <div class="col-md-3">
                            <img class="w-100" src="https://cdn.pixabay.com/photo/2015/07/24/18/38/model-858749_960_720.jpg" alt="Sale">
                        </div>
                        <div class="col-md-3">
                            <img class="w-100" src="https://cdn.pixabay.com/photo/2015/07/24/18/39/model-858751_960_720.jpg" alt="Sale">
                        </div>
                        <div class="col-md-3">
                            <img class="w-100" src="https://cdn.pixabay.com/photo/2015/07/24/18/37/model-858748_960_720.jpg" alt="Sale">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="main-description px-2">
                    <div class="category text-bold">
                        Category: <em><?= $product['category_name'] ?></em>
                    </div>
                    <div class="product-title text-bold my-3">
                        <?= $product['name'] ?>
                    </div>


                    <div class="price-area my-4">
                        <p class="old-price mb-1"><del>$100</del> <span class="old-price-discount text-danger">(20% off)</span></p>
                        <p class="new-price text-bold mb-1">$<?= $product['price'] ?></p>
                        <p class="text-secondary mb-1">(Additional tax may apply on checkout)</p>

                    </div>


                    <div class="buttons d-flex my-5">
                        <div class="block">
                            <a href="#" class="shadow btn custom-btn ">Wishlist</a>
                        </div>
                        <div class="block">
                            <button class="shadow btn custom-btn" onclick="addToCart(<?= $product['id'] ?>, <?= $product['price'] ?>, '<?= $product['name'] ?>', '<?= $images[0]['url']??'' ?>')">Add to cart</button>
                        </div>

                        <div class="block quantity">
                            <input type="number" class="form-control" id="cart_quantity" value="1" min="0" max="5" placeholder="Enter email" name="cart_quantity">
                        </div>
                    </div>




                </div>

                <div class="product-details my-4">
                    <p class="details-title text-color mb-1">Product Details</p>
                    <p class="description"><?= $product['description'] ?></p>
                </div>
              
                         <div class="row questions bg-light p-3">
                    <div class="col-md-1 icon">
                        <i class="fa-brands fa-rocketchat questions-icon"></i>
                    </div>
                    <div class="col-md-11 text">
                        Have a question about our products at E-Store? Feel free to contact our representatives via live chat or email.
                    </div>
                </div>

                <div class="delivery my-4">
                    <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-truck"></i></span> <b>Delivery done in 3 days from date of purchase</b> </p>
                    <p class="text-secondary">Order now to get this product delivery</p>
                </div>
                <div class="delivery-options my-4">
                    <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-filter"></i></span> <b>Delivery options</b> </p>
                    <p class="text-secondary">View delivery options here</p>
                </div>
                
             
            </div>
        </div>
    </div>



    <div class="container similar-products my-4">
        <hr>
        <p class="display-5">Similar Products</p>

        <div class="row">
            <div class="col-md-3">
                <div class="similar-product">
                    <img class="w-100" src="https://source.unsplash.com/gsKdPcIyeGg" alt="Preview">
                    <p class="title">Lovely black dress</p>
                    <p class="price">$100</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="similar-product">
                    <img class="w-100" src="https://source.unsplash.com/sg_gRhbYXhc" alt="Preview">
                    <p class="title">Lovely Dress with patterns</p>
                    <p class="price">$85</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="similar-product">
                    <img class="w-100" src="https://source.unsplash.com/gJZQcirK8aw" alt="Preview">
                    <p class="title">Lovely fashion dress</p>
                    <p class="price">$200</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="similar-product">
                    <img class="w-100" src="https://source.unsplash.com/qbB_Z2pXLEU" alt="Preview">
                    <p class="title">Lovely red dress</p>
                    <p class="price">$120</p>
                </div>
            </div>
        </div>
    </div>




    </div>

        <!-- show product end -->
        
        </div>
    </div>
    </div>
    <script>
        function changeImage(image, vendor_id) {
            console.log(image , vendor_id);
            document.getElementById('product-image').src = 'uploads/vendor/products/'+vendor_id+'/'+image;
        }
    </script>

    <?php require "partials/footer.php" ?>
    <script src="assets/js/cart-script.js"></script>
    </body>
</html>