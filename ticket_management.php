<?php
include 'db_connect.php';


// Function to get status text based on numeric status value
function getStatusText($statusId) {
    switch ($statusId) {
        case 1:
            return "Open";
        case 2:
            return "In Progress";
        case 3:
            return "On Hold";
        case 4:
            return "Resolved";
        case 5:
            return "Closed";
        case 6:
            return "Reopened";
        case 7:
            return "Pending Customer Response";
        case 8:
            return "Escalated";
        default:
            return "Unknown";
    }
}
// Fetch data from the ticketsubject table
$sql = "SELECT * FROM ticketsubject";
$result = mysqli_query($conn, $sql);


// Fetch ticket comments from the database
$sql = "SELECT * FROM ticketcomment";
$result2 = mysqli_query($conn, $sql);

// Initialize an empty array to store ticket comments
$ticketComments = array();

// Check if there are any ticket comments fetched
if (mysqli_num_rows($result2) > 0) {
    // Loop through each row and store ticket comments in the array
    while ($row = mysqli_fetch_assoc($result2)) {
        $ticketComments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row mt-2">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ticket Subject</h5>
                    <form action="save_subject.php" method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ticket Subject List</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Check if there are any rows returned from the query
                            if (mysqli_num_rows($result) > 0) {
                                // Loop through each row and display data in table rows
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                       echo '<td>
                                            <form action="updateTicketSubject.php" method="POST">
                                                <input type="hidden" name="update_ticket_id" value="' . $row['id'] . '">
                                                <input type="text" class="form-control" name="updated_subject" value="' . $row['subject'] . '">
                                            </td>
                                            <td>';
                                    // Update button
                                    echo '<button type="submit" class="btn btn-primary btn-sm me-2">Update</button>';
                                    
                                    // Delete button
                                    echo '<a href="deleteTicketSubject.php?id=' . $row['id'] . '&delete=true" class="btn btn-sm btn-danger delete-btn">Delete</a>';

                                    echo '</form>';
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            } else {
                                // If no rows returned, display a message
                                echo "<tr><td colspan='2'>No subjects found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

  <div class="row mt-2">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Subject Reason</h5>
                <form action="save_reason.php" method="POST">
                    <div class="form-group">
                        <label for="subject_select">Subject</label>
                        <select class="form-control" id="subject_select" name="subject" required>
                            <option value="">Select Subject</option>
                            <?php
                            // Fetch subjects from the ticketsubject table
                            $sql = "SELECT * FROM ticketsubject";
                            $result = mysqli_query($conn, $sql);

                            // Check if there are any rows returned from the query
                            if (mysqli_num_rows($result) > 0) {
                                // Loop through each row and create an option for each subject
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['subject'] . "</option>";
                                }
                            } else {
                                // If no rows returned, display a default option
                                echo "<option value='' disabled>No subjects found</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <input type="text" class="form-control" id="reason" name="reason" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Ticket Messages</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Action</th> <!-- New column for action buttons -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch ticket messages and subjects using a JOIN operation
                    $sql = "SELECT tm.id, ts.subject, tm.message 
                            FROM ticketmessage tm 
                            JOIN ticketsubject ts ON tm.subject_id = ts.id";
                    $result = mysqli_query($conn, $sql);

                    // Check if there are any rows returned from the query
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through each row and display the data in the table
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['subject'] . "</td>";
                            echo '<td>
                                    <form action="updateTicketMessage.php" method="POST">
                                        <input type="hidden" name="update_message_id" value="' . $row['id'] . '">
                                        <input type="text" class="form-control" name="updated_message" value="' . $row['message'] . '">
                                          </td>
                                            <td>

                                        <button type="submit" class="btn btn-primary btn-sm me-2">Update</button>
                                    </form>';
                            echo '<a href="deleteTicketMessage.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger">Delete</a>';
                            echo '</td>';
                            echo "</tr>";
                        }
                    } else {
                        // If no rows returned, display a message
                        echo "<tr><td colspan='4'>No messages found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <div class="row mt-2">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ticket Comments</h5>
                <form action="save_comment.php" method="POST">
                    <div class="form-group">
                        <label for="subject_select">Subject</label>
                        <select class="form-control" id="subject_select" name="subject" required>
                            <option value="">Select Subject</option>
                            <?php
// Fetch subjects from the ticketsubject table
$sql = "SELECT * FROM ticketsubject";
$result = mysqli_query($conn, $sql);

// Check if there are any rows returned from the query
if (mysqli_num_rows($result) > 0) {
    // Loop through each row and create an option for each subject
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['id'] . "'>" . $row['subject'] . "</option>";
    }
} else {
    // If no rows returned, display a default option
    echo "<option value='' disabled>No subjects found</option>";
}
?>

                        </select>
                    </div>
                    <div class="form-group">
        <label for="statusSelect">Status:</label>
        <select class="form-control " id="statusSelect" name="statusId" required>
            <option value="">Select a status...</option>
            <option value="1">Open</option>
            <option value="2">In Progress</option>
            <option value="3">On Hold</option>
            <option value="4">Resolved</option>
            <option value="5">Closed</option>
            <option value="6">Reopened</option>
            <option value="7">Pending Customer Response</option>
            <option value="8">Escalated</option>
        </select>
    </div>
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <input type="text" class="form-control" id="comment" name="comment" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
<div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ticket Comment List</h5>
                   <table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Response</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through each ticket comment and display it in a table row
        foreach ($ticketComments as $comment) {
            echo "<tr>";
            echo "<td>" . $comment['id'] . "</td>";
            
            // Fetch the subject name based on the subject ID
            $subjectId = $comment['subject_id'];
            $sql = "SELECT subject FROM ticketsubject WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $subjectId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $subject);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            
            echo "<td>" . $subject . "</td>";
            echo "<td>" . getStatusText($comment['status_id']) . "</td>"; // Display status text
            echo '<td>
                    <form action="updateTicketComment.php" method="POST">
                        <input type="hidden" name="update_comment_id" value="' . $comment['id'] . '">
                        <textarea class="form-control" name="updated_comment" rows="4">' . $comment['comment'] . '</textarea>
                    </td>
                    <td>';
            // Update button
            echo '<button type="submit" class="btn btn-primary btn-sm me-2">Update</button>';
                                        
            // Delete button
            echo '<a href="deleteTicketComment.php?id=' . $comment['id'] . '&delete=true" class="btn btn-sm btn-danger delete-btn">Delete</a>';

            echo '</form>';
            echo '</td>';
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
                </div>
            </div>
      

  

 


</div>

</div>
</body>
</html>
