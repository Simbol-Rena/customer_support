<?php
include 'db_connect.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the order ID, arrival date, and rider ID from the POST data
    $orderId = $_POST['orderId'];
    $arrivalDate = $_POST['arrivalDate'];
    $riderId = $_POST['riderId'];

    // Generate a tracking number (you can customize this as per your requirements)
    $trackingNumber = generateTrackingNumber();

    // Prepare and execute the SQL query to update the order
    $sql = "UPDATE `order` SET tracking_number = '$trackingNumber', arrival_date = '$arrivalDate', rider_id = $riderId WHERE order_id = $orderId";

    if ($conn->query($sql) === TRUE) {
        // Return a success message if the update was successful
        echo "Order updated successfully";
    } else {
        // Return an error message if there was an error with the SQL query
        echo "Error updating order: " . $conn->error;
    }
} else {
    // Return an error message if the request method is not POST
    echo "Invalid request method";
}

// Close the database connection
$conn->close();

// Function to generate a random tracking number (you can customize this as per your requirements)
function generateTrackingNumber() {
    // Generate a random 10-digit tracking number
    return 'TN' . mt_rand(1000000000, 9999999999);
}
?>
