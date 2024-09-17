<!-- /admin/section/category.php -->
<?php
require '../../config/database.php'; // Include the database connection

// Handle Add Category Form Submission
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $parent_id = $_POST['parent_id'] ? $_POST['parent_id'] : NULL;

    $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
    $stmt->bind_param("si", $category_name, $parent_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to prevent form resubmission
    header("Location: category.php");
    exit();
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete the category
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Redirect after deletion
    header("Location: category.php");
    exit();
}

// Fetch Categories
$categories = [];
$result = $conn->query("SELECT * FROM categories");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<?php include('../partials/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <?php include('../partials/sidebar.php'); ?>
        </div>
        <div class="col-9">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h2>Categories</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle"></i> Add Category
                </button>
            </div>

            <!-- Categories Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Parent Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td>
                                    <?php
                                    if ($category['parent_id']) {
                                        // Fetch parent category name
                                        $stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                                        $stmt->bind_param("i", $category['parent_id']);
                                        $stmt->execute();
                                        $stmt->bind_result($parent_name);
                                        $stmt->fetch();
                                        $stmt->close();
                                        echo htmlspecialchars($parent_name);
                                    } else {
                                        echo 'None';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="category.php?delete=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Add Category Modal -->
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" id="categoryName" name="category_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="parentCategory" class="form-label">Parent Category (Optional)</label>
                                    <select class="form-select" id="parentCategory" name="parent_id">
                                        <option value="">None</option>
                                        <?php
                                        // Fetch categories for parent selection
                                        foreach ($categories as $cat) {
                                            echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>
