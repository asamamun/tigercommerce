<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';
//if request is ajax and method is post get the values
if(isAjax()){
// 
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment'];
    $trxid = $_POST['trxid'];
    $notes = $_POST['notes'];

    if($payment == 'cod'){
        $trxid = null;
    }
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO orders (user_id, address, phone, payment, trxid, notes, created_at, updated_at) VALUES ('$user_id', '$address', '$phone', '$payment', '$trxid', '$notes', NOW(), NOW())";
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $cart_result = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
        if ($cart_result->num_rows > 0) {
            while($row = $cart_result->fetch_assoc()) {
                $product_id = $row['product_id'];
                $quantity = $row['quantity'];
                $sql = "INSERT INTO order_items (order_id, product_id, quantity, created_at, updated_at) VALUES ('$last_id', '$product_id', '$quantity', NOW(), NOW())";
                $conn->query($sql);
            }
        }
}
}
// 
}
else{
    echo json_encode(['success'=>false, "message"=>"NOT AJAX REQUEST"]);
}