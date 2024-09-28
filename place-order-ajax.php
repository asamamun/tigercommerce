<?php

session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

$json = file_get_contents('php://input');

    // Decode the JSON into a PHP associative array
    $orderData = json_decode($json, true);
// echo json_encode(['success'=>false, "message"=>$orderData]);
/*
{
    "deliverylocation": "Adabor",
    "customerName": "",
    "paymentMethod": "bkash",
    "mobileNumber": "",
    "trxid": "",
    "totalAmount": 2507,
    "cartItems": [
        {
            "id": 7,
            "price": 777,
            "title": "tttt",
            "thumbnail": "66ea8939a8f255.00691348.jpg",
            "quantity": "3"
        },
        {
            "id": 5,
            "price": 88,
            "title": "iiii",
            "thumbnail": "66ea882f9c3072.93947399.webp",
            "quantity": "2"
        }
    ]
} */

$deliveryLocation = $orderData['deliverylocation'];
$customerName = $orderData['customerName'];
$paymentMethod = $orderData['paymentMethod'];
$mobileNumber = $orderData['mobileNumber'];
$transactionId = $orderData['trxid'];
$cartItems = $orderData['cartItems'];
// echo json_encode(['success'=>false, "message"=>$cartItems]);
$userid = $_SESSION['user_id'];
$totalAmount = 0;

foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
$error = false;
//insert into orders table
$orderQuery = "INSERT INTO orders (`user_id`, `total_amount`, `transaction`, `trxid`, `address`, `phone`) VALUES ($userid, $totalAmount, '$paymentMethod', '$transactionId', '$deliveryLocation', '$mobileNumber')";
$result = $conn->query($orderQuery);
if($conn->affected_rows > 0){
    $order_id = $conn->insert_id;
    foreach ($cartItems as $item) {
        
        $productId = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $orderQuery = "INSERT INTO order_items (`order_id`, `product_id`, `quantity`, `price`) VALUES ($order_id, $productId, $quantity, $price)";
        $result2 = $conn->query($orderQuery);
        if($conn->affected_rows > 0){
            
        }
        else{
            $error = true;
        }
    }
}
else{
    $error = true;
}

if($error){
    echo json_encode(['status'=>"Error", "message"=>"Something went wrong. Please try again."]);
}
else{
    echo json_encode(['status'=>"success", "message"=>"Order placed successfully. Order ID: $order_id"]);
}


