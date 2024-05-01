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
                <form action="save_ticket_staff.php" method="post">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                  
                    <div class="col-md-12">
                       <div class="form-group">
    <label class="control-label">Subject</label>
    <select name="subject" id="subject" class="form-control form-control-sm">
        <option value="">Select Subject</option>
        <?php
        $sql = "SELECT * FROM ticketsubject ";
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
    <select name="subject_message" id="reason" class="form-control form-control-sm">
        <option value="">Select Reason</option>
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
<script>
$(document).ready(function() {
    $('#subject').change(function() {
        var subjectId = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'fetch_comments_staff.php',
            data: { subjectId: subjectId },
            success: function(response) {
                $('#reason').html(response);
            }
        });
    });
});
</script>
</body>
</html>
