<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/partials/commonfx.php';

$productsPerPage = 9; // Number of products per page

// Determine the current page and total number of pages
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, $currentPage); // Ensure current page is at least 1

// Build the base query
if (isset($_GET['category'])) {
    $cat_id = $_GET['category'];
    $baseQuery = "SELECT p.*, i.url
                  FROM products p
                  LEFT JOIN (
                      SELECT product_id, MIN(id) as min_id
                      FROM images
                      GROUP BY product_id
                  ) first_img ON p.id = first_img.product_id
                  LEFT JOIN images i ON first_img.min_id = i.id
                  WHERE p.status = 'active' AND p.category_id = $cat_id";
} else {
    $baseQuery = "SELECT p.*, i.url
                  FROM products p
                  LEFT JOIN (
                      SELECT product_id, MIN(id) as min_id
                      FROM images
                      GROUP BY product_id
                  ) first_img ON p.id = first_img.product_id
                  LEFT JOIN images i ON first_img.min_id = i.id
                  WHERE p.status = 'active'";
}

// Get the total number of products
$totalProductsResult = $conn->query("SELECT COUNT(*) as total FROM ($baseQuery) as total_query");
$totalProducts = $totalProductsResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

// Ensure current page is within bounds
$currentPage = min($totalPages, $currentPage);

// Calculate the offset for the current page
$offset = ($currentPage - 1) * $productsPerPage;

// Adjust the query to include LIMIT and OFFSET
$query = $baseQuery . " LIMIT $productsPerPage OFFSET $offset";

$productresult = $conn->query($query);
?>
<?php require "partials/header.php" ?>
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
                    <?php
                    if ($productresult->num_rows > 0) {
                        while ($row = $productresult->fetch_assoc()) {
                            echo generateProductCard($row);
                        }
                    }
                    ?>
                </div>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <?php require "partials/footer.php" ?>
</body>

</html>