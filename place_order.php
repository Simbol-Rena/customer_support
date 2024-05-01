<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Place Order</h1>
        <hr>
        <h3>Order Details</h3>
        <?php
if(isset($_GET['productName'], $_GET['productPrice'], $_GET['productQuantity'], $_GET['totalCost'], $_GET['productId'])) {
    // Retrieve other parameters
    $productId = $_GET['productId'];
    $productName = $_GET['productName'];
    $productPrice = $_GET['productPrice'];
    $productQuantity = $_GET['productQuantity'];
    $totalCost = $_GET['totalCost'];

    // Display order details
    echo "<p>Product Name: $productName</p>";
    echo "<p>Product Price: $productPrice</p>";
    echo "<p>Product Quantity: $productQuantity</p>";
    echo "<p>Total Cost: $totalCost</p>";

    // Fetch payment methods from the database
    include 'db_connect.php';
    $payment_methods_query = $conn->query("SELECT * FROM payment_method");
    if ($payment_methods_query->num_rows > 0) {
        echo '<form action="process_order.php" method="get">';
        echo '<label for="paymentMethodSelect">Select Payment Method:</label>';
        echo '<select class="form-control" id="paymentMethodSelect" name="paymentMethod">';
        while ($row = $payment_methods_query->fetch_assoc()) {
            echo '<option value="' . $row['payment_id'] . '">' . $row['method'] . '</option>';
        }
        echo '</select>';

        // Message input field
        echo '<div class="form-group mt-3">';
        echo '<label for="messageInput">Message (optional):</label>';
        echo '<input type="text" class="form-control" id="messageInput" name="message" placeholder="Enter your message">';
        echo '</div>';

        // Add hidden inputs for other order details
        echo '<input type="hidden" name="productName" value="' . urlencode($productName) . '">';
        echo '<input type="hidden" name="productPrice" value="' . $productPrice . '">';
        echo '<input type="hidden" name="productQuantity" value="' . $productQuantity . '">';
        echo '<input type="hidden" name="totalCost" value="' . $totalCost . '">';
        echo '<input type="hidden" name="productId" value="' . $productId . '">';

        // Submit button
        echo '<button type="submit" class="btn btn-primary mt-3">Place Order</button>';
        echo '</form>';
    } else {
        echo '<p>No payment methods available.</p>';
    }
} else {
    echo "<p>Invalid request. Please make sure all parameters are provided.</p>";
}


?>

    </div>
</body>
</html>
