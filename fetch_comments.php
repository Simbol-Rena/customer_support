<?php
// Include database connection
include 'db_connect.php';

// Check if statusId is set and not empty
if (isset($_POST['statusId']) && !empty($_POST['statusId'])) {
    // Sanitize the input
    $statusId = mysqli_real_escape_string($conn, $_POST['statusId']);

    // Fetch comments based on statusId
    $comments_sql = "SELECT * FROM ticketcomment WHERE status_id = '$statusId'";
    $comments_result = mysqli_query($conn, $comments_sql);

    // Check if there are any comments
    if (mysqli_num_rows($comments_result) > 0) {
        // Output comments as options for select dropdown
        while ($comment_row = mysqli_fetch_assoc($comments_result)) {
            // Truncate long comments and add them as options with title attribute
            $comment = $comment_row['comment'];
            if (strlen($comment) > 50) {
                $comment = substr($comment, 0, 50) . '...';
            }
            echo "<option value='" . $comment_row['id'] . "' title='" . htmlspecialchars($comment_row['comment']) . "'>" . htmlspecialchars($comment) . "</option>";
        }
    } else {
        echo "<option value=''>No comments found</option>";
    }
} else {
}

// Close database connection
mysqli_close($conn);
?>