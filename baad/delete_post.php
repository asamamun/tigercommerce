<?php
session_start();
require 'config/database.php';

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit();
}

// Check if the post ID is provided in the URL
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']); // Convert to integer for security

    // Prepare a DELETE SQL statement
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);

    // Deleat the photo
    $post_result = $conn->query("SELECT photo FROM posts WHERE id = $post_id");
    $post = $post_result->fetch_assoc();
    unlink($post['photo']);

    if ($stmt->execute()) {
        // Post deleted successfully
        header('Location: dashboard.php?message=Post+deleted+successfully');
    } else {
        // Handle error
        header('Location: dashboard.php?error=Error+deleting+post');
    }

    $stmt->close();
} else {
    header('Location: dashboard.php?error=Invalid+post+ID');
}
exit();
?>
