<?php
session_start();
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config/database.php';
?>
<?php require "partials/header.php" ?>
<body>
    <div class="container">
    <?php require "partials/navbar.php" ?>
    <hr>

    <?php require "partials/carousel.php" ?>
    <div class="row">
        <div class="col-3">
            <?php include('partials/sidebar.php'); ?>
        </div>
        <div class="col-9">
            
        </div>
    </div>
    </div>

    <?php require "partials/footer.php" ?>