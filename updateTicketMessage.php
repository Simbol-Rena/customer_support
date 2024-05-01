<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the update_message_id and updated_message are set and not empty
    if (isset($_POST['update_message_id']) && !empty($_POST['update_message_id']) && isset($_POST['updated_message']) && !empty($_POST['updated_message'])) {
        // Sanitize the input
        $message_id = mysqli_real_escape_string($conn, $_POST['update_message_id']);
        $updated_message = mysqli_real_escape_string($conn, $_POST['updated_message']);

        // Update the message in the ticketmessage table
        $update_sql = "UPDATE ticketmessage SET message = '$updated_message' WHERE id = '$message_id'";
        if (mysqli_query($conn, $update_sql)) {
            // If update successful, redirect to the page where the form is located
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If update fails, display an error message
            echo "Error updating message: " . mysqli_error($conn);
        }
    } else {
        // If required fields are not set, display an error message
        echo "Please provide both message ID and updated message.";
    }
} else {
    // If the form is not submitted via POST method, display an error message
    echo "Invalid request method.";
}

// Close database connection
mysqli_close($conn);
?>
