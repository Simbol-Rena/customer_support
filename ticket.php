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

// Fetch all data from the ticket table, joining with the customers table to get the full name and with the ticketmessage table to retrieve the message based on the reason_id
$sql = "SELECT t.*, 
               CONCAT(c1.firstname, ' ', c1.lastname) AS customer_name, 
               s.firstname AS staff_firstname, 
               s.lastname AS staff_lastname,
               ts.subject AS subject, 
               tm.message AS reason_message, 
               c2.comment_id AS comment_id, 
               tc.comment AS comment
        FROM ticket t
        LEFT JOIN customers c1 ON t.username = c1.id
        LEFT JOIN staff s ON t.type = 'Staff' AND t.username = s.id
        LEFT JOIN ticketsubject ts ON t.subject_id = ts.id
        LEFT JOIN (
            SELECT c.ticket_id, c.comment_id
            FROM comments c
            INNER JOIN (
                SELECT ticket_id, MAX(date) AS max_date
                FROM comments
                GROUP BY ticket_id
            ) c_max ON c.ticket_id = c_max.ticket_id AND c.date = c_max.max_date
        ) c2 ON t.ticket_id = c2.ticket_id
        LEFT JOIN ticketmessage tm ON t.reason_id = tm.id
        LEFT JOIN ticketcomment tc ON c2.comment_id = tc.id
        GROUP BY t.id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    // Handle the error appropriately, such as logging it or displaying a user-friendly message
} else {
    // Check if there are any tickets
    if (mysqli_num_rows($result) > 0) {
        // Display tickets in a table
        echo "<h1>My Tickets</h1>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Subject ID</th>
                    <th>Reason ID</th>
                    <th>Message</th>
                    <th>Image</th>
                    <th>Date</th>
                    <th>Comment</th>
                    <th>Ticket ID</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for Action -->
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . ($row['staff_firstname'] ? $row['staff_firstname'] . ' ' . $row['staff_lastname'] : $row['customer_name']) . "</td>";
            echo "<td>" . $row['subject'] . "</td>";
            echo "<td>" . $row['reason_message'] . "</td>";
            echo "<td>" . $row['message'] . "</td>";
            echo "<td>" . $row['image'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['comment'] . "</td>";
            echo "<td>" . $row['ticket_id'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . getStatusLabel($row['status']) . "</td>";
            echo "<td><button class='view-btn' 
                      data-ticket-id='" . $row['ticket_id'] . "' 
                      data-full-name='" . ($row['staff_firstname'] ? $row['staff_firstname'] . ' ' . $row['staff_lastname'] : $row['customer_name']) . "'
                      data-toggle='modal' 
                      data-target='#ticketModal'>View</button></td>"; // View button
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No tickets found.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<!-- Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Ticket Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ticketForm"> <!-- Form start -->
                <div class="modal-body" id="ticketDetails">
                    <p>Ticket ID: <span id="modalTicketId"></span></p>
                    <p>Full Name: <span id="modalFullName"></span></p>
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Status:</label>
                        <select class="form-select" id="statusSelect" name="status">
                            <option value="">Select Status</option>
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
                    <div class="mb-3">
                        <label for="commentSelect" class="form-label">Comment:</label>
                        <select class="form-select" id="commentSelect" name="comment">
                            <!-- Comments will be dynamically loaded here -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                </div>
            </form> <!-- Form end -->
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Handle click event for view button
    $('.view-btn').click(function () {
        var ticketId = $(this).data('ticket-id');
        var fullName = $(this).data('full-name');

        $('#modalTicketId').text(ticketId);
        $('#modalFullName').text(fullName);

        // Fetch comments based on ticketId using Ajax
        $.ajax({
            type: 'GET',
            url: 'fetch_comments.php',
            data: { ticketId: ticketId },
            success: function(response) {
                $('#commentSelect').html(response);
            }
        });
    });

    // Event listener for status select change
    $('#statusSelect').change(function() {
        var statusId = $(this).val();
        fetchComments(statusId);
    });

    // Initialize comments based on initial status select value
    var initialStatusId = $('#statusSelect').val();
    fetchComments(initialStatusId);
});

function fetchComments(statusId) {
    $.ajax({
        type: 'POST',
        url: 'fetch_comments.php',
        data: { statusId: statusId },
        success: function(response) {
            $('#commentSelect').html(response);
        }
    });
}


 // Event listener for status select change
    $('#statusSelect').change(function() {
        var statusId = $(this).val();
        fetchComments(statusId);
    });

    // Initialize comments based on initial status select value
    var initialStatusId = $('#statusSelect').val();
    fetchComments(initialStatusId);

    // Event listener for Save changes button
    $('#saveChangesBtn').click(function() {
        var commentId = $('#commentSelect').val(); // Get selected comment ID
        var ticketId = $('#modalTicketId').text(); // Get ticket ID

        // Send AJAX request to save_comment.php
        $.ajax({
            type: 'POST',
            url: 'save_ticket_comment.php',
            data: {
                commentId: commentId,
                ticketId: ticketId,
                statusId: $('#statusSelect').val() // Include the statusId parameter
            },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            }
        });
    });

</script>
</body>
</html>
