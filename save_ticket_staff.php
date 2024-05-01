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
    $type = 'Staff';
    
    // Assuming you have a session variable named login_id
    session_start();
    $user_id = $_SESSION['login_id'];

    // Generate random 6-character mix of letters and numbers for ticket_id
    $ticket_id = substr(uniqid(), -6);

    // Prepare SQL statement using prepared statement
    $sql = "INSERT INTO ticket (username, subject_id, reason_id, message, date, ticket_id, type, status) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "iiisssi", $user_id, $subject_id, $message_id, $additional_description, $ticket_id, $type, $status_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
         header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
    } else {
        // If insertion fails, return error response
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close database connection
    mysqli_close($conn);
}
?>
