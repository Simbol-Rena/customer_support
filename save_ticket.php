<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include 'db_connect.php';

    // Get form data
    $subject_id = $_POST['subject'];
    $message_id = $_POST['subject_message'];
    $additional_description = $_POST['description'];

    $status_id = 1;
    $type = 'Customer';
    
    // Assuming you have a session variable named login_id
    session_start();
    $user_id = $_SESSION['login_id'];

    // Generate random 6-character mix of letters and numbers for ticket_id
    $ticket_id = substr(uniqid(), -6);

    // Perform SQL query to insert data into ticket table
    $sql = "INSERT INTO ticket (username, subject_id, reason_id, message, date, ticket_id, type, status) 
            VALUES ('$user_id', '$subject_id', '$message_id', '$additional_description', NOW(), '$ticket_id', '$type', '$status_id')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // If insertion is successful, return success response
          header('Location: index.php?page=ticket_list');
    } else {
        // If insertion fails, return error response
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>
