<?php
// Include database connection
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if subject is set and not empty
    if (isset($_POST['subject']) && !empty($_POST['subject'])) {
        // Sanitize the subject input to prevent SQL injection
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);

        // SQL query to insert the subject into the ticketsubject table
        $sql = "INSERT INTO ticketsubject (subject) VALUES ('$subject')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // If insertion is successful, redirect back to the ticket management page
            header("Location: index.php?page=ticket_management");
            exit();
        } else {
            // If an error occurs, display an error message
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // If subject is not set or empty, display an error message
        echo "Subject is required";
    }
} else {
    // If the form is not submitted via POST method, redirect back to the ticket management page
    header("Location: index.php?page=ticket_management");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
