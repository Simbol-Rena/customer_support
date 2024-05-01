<?php
// Include database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present
    if (isset($_POST['ticketId'], $_POST['additional'])) {
        // Sanitize input data to prevent SQL injection (consider prepared statements)
        $ticketId = mysqli_real_escape_string($conn, $_POST['ticketId']);
        $additional = mysqli_real_escape_string($conn, $_POST['additional']);

        // Update the additional column in the ticket table
        $updateQuery = "UPDATE ticket SET message = '$additional' WHERE ticket_id = '$ticketId'";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            // Return success response
            echo 'success';
        } else {
            // Return error response
            echo 'error';
        }
    } else {
        // Return error response if required fields are missing
        echo 'error';
    }
} else {
    // Return error response for non-POST requests
    echo 'error';
}

// Close database connection
mysqli_close($conn);
?>
