<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the update_comment_id and updated_comment are set and not empty
    if (isset($_POST['update_comment_id']) && !empty($_POST['update_comment_id']) && isset($_POST['updated_comment']) && !empty($_POST['updated_comment'])) {
        // Sanitize the input
        $comment_id = mysqli_real_escape_string($conn, $_POST['update_comment_id']);
        $updated_comment = mysqli_real_escape_string($conn, $_POST['updated_comment']);

        // Update the comment in the ticketcomment table
        $update_sql = "UPDATE ticketcomment SET comment = '$updated_comment' WHERE id = '$comment_id'";
        if (mysqli_query($conn, $update_sql)) {
            // If update successful, redirect to the page where the form is located
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If update fails, display an error message
            echo "Error updating comment: " . mysqli_error($conn);
        }
    } else {
        // If required fields are not set, display an error message
        echo "Please provide both comment ID and updated comment.";
    }
} else {
    // If the form is not submitted via POST method, display an error message
    echo "Invalid request method.";
}

// Close database connection
mysqli_close($conn);
?>
