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
    // var_dump($categories);
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
    // var_dump($branch);
    return $branch;
}

// Function to generate HTML for the sidebar
/* function generateSidebar($categories, $level = 0) {
    $html = $level === 0 ? '<ul class="nav flex-column">' : '<div class="collapse" id="submenu-' . $categories[array_key_first($categories)]['parent_id'] . '">';
    foreach ($categories as $category) {
        $html .= '<li class="nav-item">';
        $html .= '<a class="nav-link' . ($level > 0 ? ' collapse-item' : '') . '" ';
        if (isset($category['children'])) {
            $html .= 'data-bs-toggle="collapse" href="#submenu-' . $category['id'] . '" role="button" aria-expanded="false" aria-controls="submenu-' . $category['id'] . '"';
        } else {
            $html .= 'href="#"';
        }
        $html .= '>';
        $html .= '<i class="bi bi-folder"></i> ' . htmlspecialchars($category['name']) . '</a>';
        if (isset($category['children'])) {
            $html .= generateSidebar($category['children'], $level + 1);
        }
        $html .= '</li>';
    }
    $html .= $level === 0 ? '</ul>' : '</div>';
    return $html;
} */
function generateSidebar($categories, $level = 0) {
    if (empty($categories)) {
        return '<p>No categories found.</p>';
    }

    $html = '<ul class="nav flex-column' . ($level > 0 ? ' collapse' : '') . '"' . 
            ($level > 0 ? ' id="submenu-' . $categories[array_key_first($categories)]['parent_id'] . '"' : '') . '>';
    
    foreach ($categories as $category) {
        $html .= '<li class="nav-item">';
        $html .= '<a class="nav-link' . ($level > 0 ? ' collapse-item' : '') . '" ';
        if (isset($category['children'])) {
            $html .= 'data-bs-toggle="collapse" href="#submenu-' . $category['id'] . '" role="button" aria-expanded="false" aria-controls="submenu-' . $category['id'] . '"';
        } else {
            $html .= 'href="#"';
        }
        $html .= '>';
        $html .= '<i class="bi bi-folder"></i> ' . htmlspecialchars($category['name']) . '</a>';
        
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
<nav class="">
                <div class="position-sticky pt-3">
                    <h3>Categories</h3>
                <?php echo $sidebarHtml; ?>
                </div>
            </nav>