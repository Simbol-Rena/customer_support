<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the update_ticket_id and updated_subject are set and not empty
    if (isset($_POST['update_ticket_id']) && !empty($_POST['update_ticket_id']) && isset($_POST['updated_subject']) && !empty($_POST['updated_subject'])) {
        // Sanitize the input
        $ticket_id = mysqli_real_escape_string($conn, $_POST['update_ticket_id']);
        $updated_subject = mysqli_real_escape_string($conn, $_POST['updated_subject']);

        // Update the subject in the ticketsubject table
        $update_sql = "UPDATE ticketsubject SET subject = '$updated_subject' WHERE id = '$ticket_id'";
        if (mysqli_query($conn, $update_sql)) {
            // If update successful, redirect to the page where the form is located
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If update fails, display an error message
            echo "Error updating subject: " . mysqli_error($conn);
        }
    } else {
        // If required fields are not set, display an error message
        echo "Please provide both ticket ID and updated subject.";
    }
} else {
    // If the form is not submitted via POST method, redirect to an error page or display a message
    echo "Invalid request method.";
}

// Close database connection
mysqli_close($conn);
?>
