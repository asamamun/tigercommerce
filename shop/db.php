<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tigercommerce";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
// Set charset to utf8
$conn->set_charset("utf8");
?>



