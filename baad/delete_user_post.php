<?php
session_start();
require 'config/database.php';
//check if user is logged in
if (!isset($_SESSION['logged_in'])) {
   die('Not logged in');
    exit();
}
$posttodelete = $_GET['id'] ?? null;

if (!$posttodelete) {
    header('Location: index.php');
    exit();
}

$selectpost = "SELECT * FROM posts WHERE id = $posttodelete limit 1";
$result = $conn->query($selectpost);
if($result->num_rows > 0) {
    $query = "update posts set deleted = CURRENT_TIMESTAMP where id = $posttodelete limit 1";
    $conn->query($query);
    if($conn->affected_rows > 0) {
        $_SESSION['message'] = 'Post deleted successfully';
        header('Location: profile.php');
    } else {
        $_SESSION['message'] = 'Error deleting post';
        header('Location: profile.php');
    }
}