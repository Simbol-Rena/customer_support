<?php
// Include database connection
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if subject, comment, and statusId are set and not empty
    if (isset($_POST['subject']) && isset($_POST['comment']) && isset($_POST['statusId']) && !empty($_POST['subject']) && !empty($_POST['comment']) && !empty($_POST['statusId'])) {
        // Sanitize the inputs to prevent SQL injection
        $subjectId = mysqli_real_escape_string($conn, $_POST['subject']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $statusId = mysqli_real_escape_string($conn, $_POST['statusId']);

        // SQL query to insert the data into the ticketcomment table
        $sql = "INSERT INTO ticketcomment (subject_id, comment, status_id) VALUES ('$subjectId', '$comment', '$statusId')";

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
        // If subject, comment, or statusId is not set or empty, display an error message
        echo "Subject, comment, and status are required";
    }
} else {
    // If the form is not submitted via POST method, redirect back to the previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
