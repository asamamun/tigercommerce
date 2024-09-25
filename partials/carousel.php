<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

// Fetch carousel images
$result = $conn->query("SELECT * FROM carousel_images where status=1 ORDER BY id DESC LIMIT 5");
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}
?>

<style>
.carousel-item img {
    width: 100%;
    height: 400px; /* Set the desired height */
    object-fit: cover; /* Ensure the image covers the area without distortion */
}
</style>

<div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators mb-3">
        <?php foreach ($images as $index => $image): ?>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active"' : '' ?> aria-current="true" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($images as $index => $image): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="uploads/<?= $image['file_path'] ?>" class="d-block w-100" alt="Carousel Image <?= $index + 1 ?>">
                <div class="carousel-caption d-none d-md-block">
                    <a href="<?= $image['url'] ?>" class="stretched-link" target="_blank">
                        <h5 class="bg-dark bg-opacity-50 p-2 rounded"><?= $image['title'] ?></h5>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>