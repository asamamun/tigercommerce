<?php include '../config/database.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    // Handle null parent_id or invalid parent_id
    if (!empty($_POST['parent_id'])) {
        $parent_id = $_POST['parent_id'];

        // Check if the parent_id exists in the categories table
        $parent_check = $conn->query("SELECT id FROM categories WHERE id = $parent_id");
        
        // If no valid parent_id is found, set it to NULL
        if ($parent_check->num_rows == 0) {
            $parent_id = 'NULL';
        }
    } else {
        $parent_id = 'NULL';  // No parent category, so set to NULL
    }

    // Prepare the SQL query
    $sql = "INSERT INTO categories (name, parent_id) VALUES ('$name', $parent_id)";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h2>Add New Category</h2>
<form action="category-create.php" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="parent_id" class="form-label">Parent Category ID</label>
        <select name="parent_id" id="">
            <option value="">None</option>
            <?php
            $sql = "SELECT id, name FROM categories WHERE parent_id IS NULL";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Category</button>
</form>


