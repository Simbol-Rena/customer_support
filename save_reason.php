<?php
// Include database connection
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if subject and reason are set and not empty
    if (isset($_POST['subject']) && isset($_POST['reason']) && !empty($_POST['subject']) && !empty($_POST['reason'])) {
        // Sanitize the inputs to prevent SQL injection
        $subjectId = mysqli_real_escape_string($conn, $_POST['subject']);
        $reason = mysqli_real_escape_string($conn, $_POST['reason']);

        // SQL query to insert the data into the ticketmessage table
        $sql = "INSERT INTO ticketmessage (subject_id, message) VALUES ('$subjectId', '$reason')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // If insertion is successful, redirect back to the previous page
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            // If an error occurs, display an error message
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // If subject or reason is not set or empty, display an error message
        echo "Subject and reason are required";
    }
} else {
    // If the form is not submitted via POST method, redirect back to the previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
