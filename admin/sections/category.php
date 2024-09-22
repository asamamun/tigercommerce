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

// Handle Edit Category Form Submission
if (isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $parent_id = $_POST['parent_id'] ? $_POST['parent_id'] : NULL;

    // Update the category
    $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
    $stmt->bind_param("sii", $category_name, $parent_id, $category_id);
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
<?php include('../partials/navbar.php'); ?>

<div class="container-fluid category-page-container">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <?php include('../partials/sidebar.php'); ?>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center my-4 category-header">
                <h2 class="category-title">Manage Categories</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle"></i>
                </button>
            </div>

            <!-- Categories Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
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
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal" 
                                                data-id="<?php echo $category['id']; ?>" 
                                                data-name="<?php echo htmlspecialchars($category['name']); ?>" 
                                                data-parent="<?php echo $category['parent_id']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
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
            </div>

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

            <!-- Edit Category Modal -->
            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="editCategoryId" name="category_id">
                                <div class="mb-3">
                                    <label for="editCategoryName" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" id="editCategoryName" name="category_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editParentCategory" class="form-label">Parent Category (Optional)</label>
                                    <select class="form-select" id="editParentCategory" name="parent_id">
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
                                <button type="submit" name="edit_category" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script>
    // Edit Category Modal
    document.addEventListener('DOMContentLoaded', function () {
        var editCategoryModal = document.getElementById('editCategoryModal');
        editCategoryModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var parent = button.getAttribute('data-parent');

            var modalIdInput = editCategoryModal.querySelector('#editCategoryId');
            var modalNameInput = editCategoryModal.querySelector('#editCategoryName');
            var modalParentSelect = editCategoryModal.querySelector('#editParentCategory');

            modalIdInput.value = id;
            modalNameInput.value = name;
            modalParentSelect.value = parent;
        });
    });
</script>

<?php include('../partials/footer.php'); ?>