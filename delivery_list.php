<?php
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['login_id'])) {
    // Redirect the user to the login page or display an error message
    header('location: login.php');
    exit;
}

// Retrieve the login ID of the rider from the session
$riderId = $_SESSION['login_id'];

// Fetch orders assigned to the logged-in rider from the database
$query = "SELECT o.order_id, o.tracking_number,o.delivery_proof_image, p.name AS product_name, p.image AS product_image, o.quantity, p.price, o.order_date, o.status, pm.method AS payment_method, o.customer_message FROM `order` o JOIN product p ON o.product_id = p.product_id JOIN payment_method pm ON o.mop = pm.payment_id WHERE o.rider_id = $riderId";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery List</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
   <form id="update-form" method="post" enctype="multipart/form-data" action="update_status.php">
    <div class="container">
        <h1>Delivery List</h1>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered">
                <!-- Table header -->
                <thead>
    <tr>
        <th>Tracking Number</th>
        <th>Product Details</th>
        <th>Total Cost</th>
        <th>Order Date</th>
        <th>Payment Method</th>
        <th>Current Status</th>
        <th>Action</th>
    </tr>
</thead>
<!-- Table body -->
<tbody>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Calculate total cost
            $totalCost = $row['quantity'] * $row['price'];
            echo "<tr>";
echo "<td>";
echo $row["tracking_number"]; // Output the tracking number
echo "<br>"; // Line break
echo "<select class='form-control' name='status'>"; // Start of the dropdown menu
echo "<option value='Preparing to ship'>Preparing to ship</option>";
echo "<option value='Your parcel has been picked up by our logistics partner'>Your parcel has been picked up by our logistics partner</option>";
echo "<option value='At SOC5 sorting facility'>At SOC5 sorting facility</option>";
echo "<option value='Departing from sorting facility'>Departing from sorting facility</option>";
echo "<option value='At SOC4 sorting facility'>At SOC4 sorting facility</option>";
echo "<option value='Departed from sorting facility'>Departed from sorting facility</option>";
echo "<option value='Parcel has arrived and to be received by the delivery hub'>Parcel has arrived and to be received by the delivery hub</option>";
echo "<option value='At delivery hub'>At delivery hub</option>";
echo "<option value='Parcel is out for delivery'>Parcel is out for delivery</option>";
echo "<option value='In transit'>In transit</option>";
echo "<option value='Delivered'>Delivered</option>";
echo "</select>"; // End of the dropdown menu

// JavaScript code to handle the image upload form display
echo "<script>";
echo "document.addEventListener('DOMContentLoaded', function() {";
echo "var selectStatus = document.querySelector('select[name=\"status\"]');";
echo "selectStatus.addEventListener('change', function() {";
echo "if (selectStatus.value === 'Delivered') {";
echo "document.getElementById('image-upload-form').style.display = 'block';";
echo "} else {";
echo "document.getElementById('image-upload-form').style.display = 'none';";
echo "}";
echo "});";
echo "});";
echo "</script>";

// Image upload form
echo "<div id='image-upload-form' style='display: none;'>";
echo "<input type='hidden' name='order_id' value='" . $row['order_id'] . "'>"; // Hidden field to store order ID
echo "<input type='file' name='proof_image' accept='image/*'>"; // File input for uploading the image
echo "</div>";

echo "</td>";


            echo "<td>";
            echo "<strong>Name:</strong> " . $row["product_name"] . "<br>";
            echo "<strong>Quantity:</strong> " . $row["quantity"] . "<br>";
            echo "<strong>Price:</strong> $" . $row["price"] . "<br>";
            echo "<img src='data:image/jpeg;base64," . base64_encode($row["product_image"]) . "' alt='Product Image' style='height: 100px;'>";
            echo "</td>";
            echo "<td>" . $totalCost . "</td>";
            echo "<td>" . $row["order_date"] . "</td>";
            echo "<td>";
echo "" . $row["payment_method"] . "<br>";
echo "<strong>Customer Message:</strong> " . $row["customer_message"];
echo "</td>";

          echo "<td>";
echo $row["status"]; // Display the status

// Check if delivery_proof_image is not null
if ($row["delivery_proof_image"] != null) {
    // Display text indicating Proof of delivery
    echo "<br><br>";
    echo "<strong>Proof of delivery: </strong><br>";

    // Display the proof image
    echo "<img src='" . $row["delivery_proof_image"] . "' alt='Proof Image' style='height: 100px;'>";
}

echo "</td>";

         echo "<td>";
echo $row["status"]; // Display the status

// Check if the status is one of the statuses that should disable the update button
$disableButton = ($row["status"] === "Delivered" || $row["status"] === "Order Received" || $row["status"] === "Refund");

if ($disableButton) {
    // Disable the update button
    echo "<button type='button' class='btn btn-primary btn-update' data-id='" . $row['order_id'] . "' disabled>Update</button>";
} else {
    // Enable the update button
    echo "<button type='submit' class='btn btn-primary btn-update' data-id='" . $row['order_id'] . "'>Update</button>";
}

echo "</td>";



            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No orders found</td></tr>";
    }
    ?>
</tbody>

            </table>
        </div>
    </div>
    </form>

 <script type="text/javascript">
    // Add event listener to Update button
    document.querySelectorAll('.btn-update').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id'); // Get order ID
            const status = this.parentElement.parentElement.querySelector('select[name="status"]').value; // Get selected status
            const formData = new FormData(); // Create a FormData object to send both status and image data

            // Add status and image data to FormData object
            formData.append('order_id', orderId);
            formData.append('status', status);
            formData.append('proof_image', document.querySelector('input[name="proof_image"]').files[0]);

            // AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_status.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Handle response if needed
                    console.log(xhr.responseText);
                } else {
                    // Handle error
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Request failed');
            };
            xhr.send(formData); // Send FormData object containing both status and image data
        });
    });
</script>


</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
