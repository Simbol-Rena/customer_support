<?php
// Include database connection
include 'db_connect.php';

// Check if commentId, ticketId, and statusId are set and not empty
if (isset($_POST['commentId']) && !empty($_POST['commentId']) &&
    isset($_POST['ticketId']) && !empty($_POST['ticketId']) &&
    isset($_POST['statusId']) && !empty($_POST['statusId'])) {

    // Sanitize the inputs
    $commentId = mysqli_real_escape_string($conn, $_POST['commentId']);
    $ticketId = mysqli_real_escape_string($conn, $_POST['ticketId']);
    $statusId = mysqli_real_escape_string($conn, $_POST['statusId']);

    // Insert the comment into the comments table
    $insert_query = "INSERT INTO comments (ticket_id, comment_id, date) VALUES ('$ticketId', '$commentId', NOW())";
    if (mysqli_query($conn, $insert_query)) {
        // Update the ticket status in the database
        $update_sql = "UPDATE ticket SET status = '$statusId' WHERE ticket_id = '$ticketId'";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            echo "Status updated successfully.";
        } else {
            echo "Error updating status: " . mysqli_error($conn);
        }
        echo "Comment saved successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid comment ID, ticket ID, or status ID";
}

// Close database connection
mysqli_close($conn);
?>
