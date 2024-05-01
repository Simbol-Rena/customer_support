<?php

// Include database connection
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['login_id'])) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

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

// Get login ID from session
$loginId = $_SESSION['login_id'];

// Fetch tickets from database based on user ID
$sql = "SELECT t.*, ts.subject, tm.message AS comment
        FROM ticket t 
        JOIN ticketsubject ts ON t.subject_id = ts.id
        JOIN ticketmessage tm ON t.reason_id = tm.id
        WHERE t.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loginId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>My Tickets</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Subject</th>
                <th>Reason</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th> <!-- New column for actions -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are tickets
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ticket_id"] . "</td>";
                    echo "<td>" . $row["subject"] . "</td>";
                    echo "<td>" . $row["comment"] . "</td>";
                    echo "<td>" . $row["message"] . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "<td>" . getStatusLabel($row['status']) . "</td>";
                     echo "<td>";
                    // Delete button
                    echo "<form action='deleteTicketStaff.php' method='post'>";
                    echo "<input type='hidden' name='ticket_id' value='" . $row['ticket_id'] . "'>";
                    echo "<button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this ticket?\")'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No tickets found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Close statement and database connection
$stmt->close();
$conn->close();
?>
