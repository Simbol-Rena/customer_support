<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
if (!isset($_SESSION['login_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db_connect.php';

function getStatusLabel($status) {
    switch ($status) {
        case 1: // Open
            return 'Open';
        case 2: // In Progress
            return 'In Progress';
        case 3: // On Hold
            return 'On Hold';
        case 4: // Resolved
            return 'Resolved';
        case 5: // Closed
            return 'Closed';
        case 6: // Reopened
            return 'Reopened'; 
        case 7: // Pending Customer Response
            return 'Pending Customer Response';
        case 8: // Escalated
            return 'Escalated';
        default:
            return 'Unknown'; // Default label for unknown statuses
    }
}

// Get the logged-in user's ID
$user_id = $_SESSION['login_id'];

$sql = "SELECT t.ticket_id, ts.subject, tm.message, t.date, t.status, t.message AS additional
        FROM ticket t
        LEFT JOIN ticketsubject ts ON t.subject_id = ts.id
        LEFT JOIN ticketmessage tm ON t.reason_id = tm.id
        WHERE t.username = '$user_id'";

$result = mysqli_query($conn, $sql);

// Check if there are any tickets
if (mysqli_num_rows($result) > 0) {
    // Display tickets in a table
    echo "<div class='container'>";
    echo "<h1>My Tickets</h1>";
    echo "<table border='1'>";
    echo "<thead>
            <tr>
                <th>Ticket ID</th>
                <th>Subject</th>
                <th>Reason</th>
                <th>Additional</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th> <!-- New column for actions -->
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['ticket_id'] . "</td>";
        echo "<td>" . $row['subject'] . "</td>";
        echo "<td>" . $row['message'] . "</td>";
        echo "<td>" . $row['additional'] . "</td>";
        // echo "<td><input type='text' class='form-control additional-input' value='" . $row['additional'] . "'></td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "<td>" . getStatusLabel($row['status']) . "</td>";
        echo "<td>";
        // Edit and delete buttons
        // if ($row['status'] === '1') {
        //     // If status is "Open", enable the edit button
        //     echo "<button type='button' class='btn btn-primary update-btn' data-ticket-id='" . $row['ticket_id'] . "'>Update</button>";
        // }
        echo "<a href='deleteTicket.php?id=" . $row['ticket_id'] . "' class='btn btn-danger'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>"; // Close container
} else {
    echo "<p class='container'>No tickets found.</p>";
}

// Close database connection
mysqli_close($conn);
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Update button click event
    $('.update-btn').click(function() {
        var ticketId = $(this).data('ticket-id');
        var additional = $(this).closest('tr').find('.additional-input').val();
        // Send AJAX request to update additional column
        $.post('updateTicket.php', { ticketId: ticketId, additional: additional }, function(response) {
            // Check response and handle accordingly (redirect, display message, etc.)
            if (response === 'success') {
                // Reload page after successful update
                location.reload();
            } else {
                // Display error message or handle error case
                console.log('Error updating ticket');
            }
        });
    });
});
</script>

</body>
</html>
