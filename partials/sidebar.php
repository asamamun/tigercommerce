<?php
require __DIR__."/../config/database.php";

// Function to get all categories
function getCategories($conn) {
    $categories = [];
    $result = $conn->query("SELECT * FROM categories ORDER BY parent_id, name");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[$row['id']] = $row;
        }
        $result->free();
    }
    return $categories;
}

// Function to build the category tree
function buildCategoryTree($categories, $parentId = null) {
    $branch = [];
    foreach ($categories as $category) {
        if ($category['parent_id'] == $parentId) {
            $children = buildCategoryTree($categories, $category['id']);
            if ($children) {
                $category['children'] = $children;
            }
            $branch[$category['id']] = $category;
        }
    }
    return $branch;
}

// Function to generate HTML for the sidebar
function generateSidebar($categories, $level = 0) {
    if (empty($categories)) {
        return '<p class="text-muted">No categories found.</p>';
    }

    $html = '<ul class="nav flex-column' . ($level > 0 ? ' collapse' : '') . '"' . 
            ($level > 0 ? ' id="submenu-' . $categories[array_key_first($categories)]['parent_id'] . '"' : '') . '>';
    
    foreach ($categories as $category) {
        $html .= '<li class="nav-item">';
        $html .= '<a class="nav-link' . ($level > 0 ? ' collapse-item' : '') . ' d-flex align-items-center" ';
        if (isset($category['children'])) {
            $html .= 'data-bs-toggle="collapse" href="#submenu-' . $category['id'] . '" role="button" aria-expanded="false" aria-controls="submenu-' . $category['id'] . '"';
        } else {
            $html .= 'href="index.php?category=' . $category['id'] . '"';
        }
        $html .= '>';
        $html .= '<i class="bi bi-folder me-2"></i> ' . htmlspecialchars($category['name']) . '</a>';
        
        if (isset($category['children'])) {
            $html .= generateSidebar($category['children'], $level + 1);
        }
        
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    return $html;
}

// Get categories and build the tree
$allCategories = getCategories($conn);
$categoryTree = buildCategoryTree($allCategories);

// Generate the sidebar HTML
$sidebarHtml = generateSidebar($categoryTree);

// Close the database connection
// $conn->close();
?>
<nav class="sidebar bg-light p-3 rounded">
    <div class="position-sticky pt-3">
        <h3 class="mb-3">Categories</h3>
        <?php echo $sidebarHtml; ?>
    </div>
</nav>
