<?php
function generateProductCard($product) {
    $imageUrl = "uploads/vendor/products/".$product['vendor_id']."/" . $product['url']; // Adjust this path as needed
    $html = <<<HTML
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a class="text-decoration-none" href="product-details.php?id={$product['id']}">
            <img src="{$imageUrl}" class="card-img-top" alt="{$product['name']}" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title text-truncate">{$product['name']}</h5>
                <p class="card-text">{$product['description']}</p>
                <p class="card-text"><strong>Price: $</strong>{$product['price']}</p>
                <p class="card-text"><strong>Stock: </strong>{$product['stock_quantity']}</p>
            </div>
            </a>
            <div class="card-footer">
                <small class="text-muted">Last updated: {$product['updated_at']}</small>
            </div>
        </div>
    </div>
HTML;
    return $html;
}