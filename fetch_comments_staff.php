<?php
if (!isset($conn)) {
    include 'db_connect.php';
}

// Check if subjectId is set in the POST request
if (isset($_POST['subjectId'])) {
    $subjectId = $_POST['subjectId'];

    // Fetch comments from the database based on the selected subject ID
    $sql = "SELECT * FROM ticketmessage WHERE subject_id = $subjectId";
    $result = mysqli_query($conn, $sql);

    // Check if there are any rows returned from the query
    if (mysqli_num_rows($result) > 0) {
        // Loop through each row and create an option for each message
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . $row['message'] . "</option>";
        }
    } else {
        // If no rows returned, display a default option
        echo "<option value='' disabled>No messages found</option>";
    }
} else {
    // If subjectId is not set in the POST request, return an error message
    echo "Error: Subject ID not provided";
}
?>
