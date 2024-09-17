<h1>Manage Products</h1>
<?php
// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
echo '<table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>';
while($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['description'] . '</td>
            <td>' . $row['price'] . '</td>
            <td>' . $row['stock_quantity'] . '</td>
            <td><a href="edit_product.php?id=' . $row['id'] . '">Edit</a> | 
                <a href="delete_product.php?id=' . $row['id'] . '">Delete</a>
            </td>
          </tr>';
}
echo '</table>';
?>
