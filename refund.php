<?php
if (!isset($conn)) {
    include 'db_connect.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ticket</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="save_ticket.php" method="post">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                  
                    <div class="col-md-12">
                       <div class="form-group">
    <label class="control-label">Subject</label>
    <select name="subject" class="form-control form-control-sm">
        <option value="">Select Subject</option>
        <?php
        // Fetch subjects containing "refund" or "return" from the ticketsubject table
        $sql = "SELECT * FROM ticketsubject WHERE subject LIKE '%refund%' OR subject LIKE '%return%'";
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
    <label class="control-label">Reason</label>
    <select name="subject_message" class="form-control form-control-sm">
        <option value="">Select Reason</option>
       <?php
// Fetch messages from the ticketmessage table based on subject ID from ticketsubject table
$sql_message = "SELECT tm.* FROM ticketmessage tm
                JOIN ticketsubject ts ON tm.subject_id = ts.id
                WHERE ts.subject LIKE '%refund%' OR ts.subject LIKE '%return%'";
$result_message = mysqli_query($conn, $sql_message);

// Check if there are any rows returned from the query
if (mysqli_num_rows($result_message) > 0) {
    // Loop through each row and create an option for each message
    while ($row_message = mysqli_fetch_assoc($result_message)) {
        echo "<option value='" . $row_message['id'] . "'>" . $row_message['message'] . "</option>";
    }
} else {
    // If no rows returned, display a default option
    echo "<option value='' disabled>No messages found</option>";
}
?>

    </select>
</div>

                        <div class="form-group">
                            <label class="control-label">Additional Description(Optional)</label>
                            <textarea name="description" id="" cols="30" rows="10" class="form-control summernote" ><?php echo isset($description) ? $description : '' ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 text-right justify-content-center d-flex">
                        <button class="btn btn-primary mr-2">Save</button>
                        <button class="btn btn-secondary" type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.js"></script>

</body>
</html>
