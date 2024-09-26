<?php
session_start();
require __DIR__ . '/vendor/autoload.php'; // Dompdf autoload
require __DIR__ . '/config/database.php';
use Dompdf\Dompdf;

function generateReceipt($orderId, $totalAmount, $paymentMethod) {
    // Generate HTML for the receipt
    $receiptHTML = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                padding: 20px;
                margin: auto;
                width: 80%;
                max-width: 600px;
                border: 1px solid #ccc;
                border-radius: 10px;
                background-color: #f7f7f7;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
                border-bottom: 2px solid #4CAF50;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
                color: #4CAF50;
            }
            .content {
                margin-top: 20px;
            }
            .content h3 {
                margin-bottom: 10px;
                font-size: 18px;
            }
            .content p {
                font-size: 16px;
                margin: 0;
            }
            .footer {
                text-align: center;
                padding-top: 20px;
                border-top: 2px solid #4CAF50;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="receipt-container">
            <div class="header">
                <h1>Order Receipt</h1>
            </div>
            <div class="content">
                <h3>Order ID: ' . $orderId . '</h3>
                <p>Total Amount: $' . number_format($totalAmount, 2) . '</p>
                <p>Payment Method: ' . ucfirst($paymentMethod) . '</p>
                <p>Order Date: ' . date('Y-m-d') . '</p>
            </div>
            <div class="footer">
                <p>Thank you for your purchase!</p>
                <p>TigerCommerce</p>
            </div>
        </div>
    </body>
    </html>
    ';

    // Initialize dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($receiptHTML);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the generated PDF (force download)
    $dompdf->stream("receipt_{$orderId}.pdf", array("Attachment" => 1));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && $_SESSION['role'] == 'customer') {
        $userId = $_SESSION['user_id'];
        $totalAmount = $_POST['total_amount'];
        $paymentMethod = $_POST['payment_method'];

        // Insert the order into the orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("id", $userId, $totalAmount);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // Insert order items (assuming cart data is in session)
        foreach ($_SESSION['cart'] as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        $stmt->close();

        // Generate and download the beautiful receipt as PDF
        generateReceipt($orderId, $totalAmount, $paymentMethod);
    } else {
        echo 'You must be logged in to place an order.';
    }
}
?>

<?php require "partials/header.php"; ?>
</head>
<body>
    <?php require "partials/navbar.php"; ?>
    <div class="container main-content">
        <div class="container mt-5">
            <h2 class="text-center mb-4">Cart Details</h2>
            <!-- Cart details -->
            <h3 class="text-center mb-4">Grand Total: <span id="grandTotal"></span></h3>

            <!-- Payment form start -->
            <form method="POST" action="place-order.php">
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Select Payment Method:</label>
                    <select class="form-select" name="payment_method" id="paymentMethod" required>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="cash_on_delivery">Cash on Delivery</option>
                    </select>
                </div>
                <input type="hidden" name="total_amount" id="totalAmount" value="">
                <button type="submit" name="place_order" class="btn btn-primary btn-lg">Place Order</button>
            </form>
            <!-- Payment form end -->
        </div>
    </div>
    <?php require "partials/footer.php"; ?>
    <script>
        const grandTotal = cart.getTotalPrice();
        document.getElementById('grandTotal').innerHTML = grandTotal;
        document.getElementById('totalAmount').value = grandTotal;
    </script>
</body>
</html>
