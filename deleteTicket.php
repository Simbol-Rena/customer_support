<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['login_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db_connect.php';

// Check if the ticket ID is provided via GET request
if(isset($_GET['id'])) {
    // Sanitize the ticket ID
    $ticket_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Prepare SQL statement to delete the ticket
    $sql = "DELETE FROM ticket WHERE ticket_id = '$ticket_id'";
    
    // Execute the SQL statement
    if(mysqli_query($conn, $sql)) {
        // Ticket deleted successfully
         header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
    } else {
        // Error deleting ticket
        echo "Error deleting ticket: " . mysqli_error($conn);
    }
} else {
    // Ticket ID not provided
    echo "Ticket ID not provided.";
}

// Close database connection
mysqli_close($conn);
?>
