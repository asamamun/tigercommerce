<?php
session_start();
require __DIR__ . '/vendor/autoload.php'; // Dompdf autoload
require __DIR__ . '/config/database.php';

use Dompdf\Dompdf;

// Clear previous output
ob_start();

function generateReceipt($orderId, $totalAmount, $paymentMethod, $deliveryLocation, $transactionId, $customerName)
{
    // Add TigerCommerce logo (replace with your logo path)
    $logoPath = 'uploads/logo.jpg'; // Adjusted path
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
            .header img {
                margin-bottom: 10px;
                height: 50px;
                width: 50px; 
                border-radius: 50%;
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
                <img src="' . $logoPath . '" alt="TigerCommerce Logo">
                <h1>Order Receipt - TigerCommerce</h1>
            </div>
            <div class="content">
                <h3>Order ID: ' . $orderId . '</h3>
                <p>Customer Name: ' . htmlspecialchars($customerName) . '</p>
                <p>Total Amount: $' . number_format($totalAmount, 2) . '</p>
                <p>Payment Method: ' . ucfirst($paymentMethod) . '</p>
                <p>Transaction ID: ' . htmlspecialchars($transactionId) . '</p>
                <p>Delivery Location: ' . htmlspecialchars($deliveryLocation) . '</p>
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

    // Clear output buffer and stream PDF
    ob_end_clean();
    $dompdf->stream("receipt_{$orderId}.pdf", array("Attachment" => 1));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && $_SESSION['role'] == 'customer') {
        $userId = $_SESSION['user_id'];
        $customerName = $_POST['customer_name'];
        $deliveryLocation = $_POST['delivery_location'];
        $totalAmount = $_POST['total_amount'];
        $paymentMethod = $_POST['payment_method'];
        $transactionId = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : 'N/A';
        $orderDate = date('Y-m-d H:i:s');

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

        // Ask if the user wants to download the receipt
        echo "<script>
         if (confirm('Your order has been placed. Do you want to download the receipt?')) {
             window.location.href = 'place-order.php?download_receipt=1&order_id={$orderId}';
         }
     </script>";
    } else {
        echo 'You must be logged in to place an order.';
    }
    // Generate and download the receipt with actual data
    generateReceipt($orderId, $totalAmount, $paymentMethod, $deliveryLocation, $transactionId, $customerName, $orderDate);
}
?>

<?php require "partials/header.php"; ?>
</head>

<body>
    <?php require "partials/navbar.php"; ?>
    <div class="container main-content">
        <div class="container w-50 m-auto">
            <h2 class="text-center mb-4">Cart Details</h2>
            <!-- Cart details -->
            <h3 class="text-center mb-4">Grand Total: <span id="grandTotal"></span></h3>

            <!-- Payment form start -->
            <form method="POST" action="place-order.php">
                <div class="mb-3">
                    <label for="deliveryLocation" class="form-label">Select Delivery Location:</label>
                    <select class="form-select" name="delivery_location" id="deliveryLocation" required>
                        <option value="Adabor">Adabor</option>
                        <option value="Ashulia">Ashulia</option>
                        <option value="Azimpur">Azimpur</option>
                        <option value="Badda">Badda</option>
                        <option value="Banani">Banani</option>
                        <option value="Banasree">Banasree</option>
                        <option value="Baridhara">Baridhara</option>
                        <option value="Bashabo">Bashabo</option>
                        <option value="Bashundhara">Bashundhara</option>
                        <option value="Bijoy Sarani">Bijoy Sarani</option>
                        <option value="Chakbazar">Chakbazar</option>
                        <option value="Demra">Demra</option>
                        <option value="Dhanmondi">Dhanmondi</option>
                        <option value="Dohar">Dohar</option>
                        <option value="Elephant Road">Elephant Road</option>
                        <option value="Farmgate">Farmgate</option>
                        <option value="Gulshan">Gulshan</option>
                        <option value="Hazaribagh">Hazaribagh</option>
                        <option value="Jatrabari">Jatrabari</option>
                        <option value="Kamrangirchar">Kamrangirchar</option>
                        <option value="Kalyanpur">Kalyanpur</option>
                        <option value="Kazipara">Kazipara</option>
                        <option value="Kawran Bazar">Kawran Bazar</option>
                        <option value="Keraniganj">Keraniganj</option>
                        <option value="Khilgaon">Khilgaon</option>
                        <option value="Khilkhet">Khilkhet</option>
                        <option value="Kuril">Kuril</option>
                        <option value="Lalbagh">Lalbagh</option>
                        <option value="Malibagh">Malibagh</option>
                        <option value="Mirpur">Mirpur</option>
                        <option value="Moghbazar">Moghbazar</option>
                        <option value="Mohakhali">Mohakhali</option>
                        <option value="Mohammadpur">Mohammadpur</option>
                        <option value="Motijheel">Motijheel</option>
                        <option value="Nawabganj">Nawabganj</option>
                        <option value="Paltan">Paltan</option>
                        <option value="Rampura">Rampura</option>
                        <option value="Savar">Savar</option>
                        <option value="Shahbag">Shahbag</option>
                        <option value="Shakertek">Shakertek</option>
                        <option value="Shantinagar">Shantinagar</option>
                        <option value="Shyamoli">Shyamoli</option>
                        <option value="Shyampur">Shyampur</option>
                        <option value="Tejgaon">Tejgaon</option>
                        <option value="Tongi">Tongi</option>
                        <option value="Uttara">Uttara</option>
                        <option value="Wari">Wari</option>

                        <!-- Add other areas here -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="customerName" class="form-label">Customer Name:</label>
                    <input type="text" class="form-control" id="customerName" name="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Select Payment Method:</label>
                    <select class="form-select" name="payment_method" id="paymentMethod" required>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="upay">Upay</option>
                        <option value="rocket">Rocket</option>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                </div>

                <!-- Mobile Number and Transaction ID fields for bKash/Nagad -->
                <div id="paymentDetails" style="display: block;">
                    <div class="mb-3">
                        <label for="mobileNumber" class="form-label">bKash/Nagad Mobile Number (11 digits):</label>
                        <input type="text" class="form-control" id="mobileNumber" name="mobile_number" pattern="\d{11}" title="Please enter an 11-digit phone number">
                    </div>
                    <div class="mb-3">
                        <label for="transactionId" class="form-label">Transaction ID:</label>
                        <input type="text" class="form-control" id="transactionId" name="transaction_id">
                    </div>
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

        const paymentMethodSelect = document.getElementById('paymentMethod');
        const paymentDetails = document.getElementById('paymentDetails');
        const mobileNumberInput = document.getElementById('mobileNumber');
        const transactionIdInput = document.getElementById('transactionId');

        // Show Mobile Number and Transaction ID inputs for bKash/Nagad
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'bkash' || this.value === 'nagad') {
                paymentDetails.style.display = 'block';
                mobileNumberInput.required = true;
                transactionIdInput.required = true;
            } else {
                paymentDetails.style.display = 'none';
                mobileNumberInput.required = false;
                transactionIdInput.required = false;
                mobileNumberInput.value = ''; // Clear the fields if not needed
                transactionIdInput.value = '';
            }
        });
    </script>
</body>

</html>