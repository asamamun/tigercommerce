<h1>Manage Orders</h1>
<?php
// Fetch orders
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
echo '<table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>';
while($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['user_id'] . '</td>
            <td>' . $row['total_amount'] . '</td>
            <td>' . $row['status'] . '</td>
            <td><a href="view_order.php?id=' . $row['id'] . '">View</a> | 
                <a href="delete_order.php?id=' . $row['id'] . '">Delete</a>
            </td>
          </tr>';
}
echo '</table>';
?>
