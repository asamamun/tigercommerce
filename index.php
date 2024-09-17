<?php
session_start();
$page = $_GET['page'] ?? 1;
$count = $_GET['count'] ?? 6;
require 'vendor/autoload.php';
require 'config/database.php';

use Carbon\Carbon;

// Ensure the user is logged in and the user ID is available in the session
/* if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle the error
    header('Location: login.php');
    exit;
}

$id = $_SESSION['user_id']; */


echo config('poly.project') . "<br>";
echo config('test.course') . "<br>";
?>
