<?php
include 'config/database.php'; // Ensure the correct path to your config file

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Check if the database connection is established
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        // Redirect back to the dashboard with a success message
        header("Location: dashboard.php?message=User+deleted+successfully");
    } else {
        // Redirect back to the dashboard with an error message
        header("Location: dashboard.php?error=Failed+to+delete+user");
    }
    
    $stmt->close();
} else {
    // Redirect back to the dashboard with an error message
    header("Location: dashboard.php?error=Invalid+user+ID");
}

$conn->close();
?>