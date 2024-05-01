<?php
// Include database connection
include 'db_connect.php';

// Check if the ticket ID is provided via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id'])) {
    // Get the ticket ID from POST data
    $ticketId = $_POST['ticket_id'];

    // Prepare a DELETE statement to remove the ticket from the database
    $deleteStatement = $conn->prepare("DELETE FROM ticket WHERE ticket_id = ?");
    $deleteStatement->bind_param("i", $ticketId); // 'i' indicates integer type for the ticket ID
    $deleteStatement->execute();

    // Check if the deletion was successful
    if ($deleteStatement->affected_rows > 0) {
        // Redirect back to the page displaying the tickets
         header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
    } else {
        // Handle deletion failure (e.g., display an error message)
        echo "Failed to delete ticket.";
    }

    // Close the prepared statement
    $deleteStatement->close();
} else {
    // Handle case where ticket ID is not provided via POST
    echo "Ticket ID not provided.";
}

// Close database connection
$conn->close();
?>
