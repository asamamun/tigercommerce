<h1>Manage Vendors</h1>
<?php
// Fetch vendors
$sql = "SELECT * FROM vendors";
$result = $conn->query($sql);
echo '<table border="1">
        <tr>
            <th>ID</th>
            <th>Company Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>';
while($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['company_name'] . '</td>
            <td>' . $row['description'] . '</td>
            <td>' . $row['status'] . '</td>
            <td><a href="edit_vendor.php?id=' . $row['id'] . '">Edit</a> | 
                <a href="delete_vendor.php?id=' . $row['id'] . '">Delete</a>
            </td>
          </tr>';
}
echo '</table>';
?>
