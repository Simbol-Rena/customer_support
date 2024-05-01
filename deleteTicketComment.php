<?php
// Include database connection
include 'db_connect.php';

// Check if the delete request is made via GET method
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if the 'id' parameter is set in the URL
    if (isset($_GET['id'])) {
        // Sanitize the ID parameter to prevent SQL injection
        $comment_id = mysqli_real_escape_string($conn, $_GET['id']);

        // Construct the delete query
        $delete_sql = "DELETE FROM ticketcomment WHERE id = '$comment_id'";

        // Execute the delete query
        if (mysqli_query($conn, $delete_sql)) {
            // If delete successful, redirect to the page where the form is located
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If delete fails, display an error message
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        // If the 'id' parameter is not set, display an error message
        echo "ID parameter is missing.";
    }
} else {
    // If the request method is not GET, display an error message
    echo "Invalid request method.";
}

// Close database connection
mysqli_close($conn);
?>
