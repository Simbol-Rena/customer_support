<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if(!isset($_SESSION['login_id'])) {
    // Redirect the user to the login page or display an error message
    header('location: login.php');
    exit;
}

if(isset($_GET['productName'], $_GET['productPrice'], $_GET['productQuantity'], $_GET['paymentMethod'])) {
    // Retrieve parameters from the URL query string
    $productName = $_GET['productName'];
    $productPrice = $_GET['productPrice'];
    $productQuantity = $_GET['productQuantity'];
    $paymentMethod = $_GET['paymentMethod'];
    $message = isset($_GET['message']) ? $_GET['message'] : ''; // Optional message

    // Retrieve the user ID of the logged-in customer from the session
    $userId = $_SESSION['login_id'];

    $status = "Order placed";

    // Get the current date and time
    $orderDate = date('Y-m-d H:i:s');

    // Assuming product_id is retrieved from the URL query string
    $productId = $_GET['productId'];

    // Insert the order into the database
    $insertOrderQuery = $conn->prepare("INSERT INTO `order` (user_id, product_id, quantity, order_date, status, customer_message, mop) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertOrderQuery->bind_param("iidssss", $userId, $productId, $productQuantity, $orderDate, $status, $message, $paymentMethod);

    if ($insertOrderQuery->execute()) {
        // Order successfully placed
        header('Location: orders.php');
        exit;
    } else {
        // Failed to place order
        echo "Error: " . $conn->error;
    }

    $insertOrderQuery->close();
} else {
  
        echo "Invalid request. Please make sure all parameters are provided.";
    }


// Close the database connection
$conn->close();
?>
