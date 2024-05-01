<?php
include 'db_connect.php';

// Check if the user is logged in
if(!isset($_SESSION['login_id'])) {
    // Redirect the user to the login page or display an error message
    header('location: login.php');
    exit;
}

// Retrieve the user ID of the logged-in customer from the session
$userId = $_SESSION['login_id'];

// Fetch orders associated with the logged-in user from the database, joining with the product, payment_method, and staff tables
$query = "SELECT o.order_id, o.tracking_number, p.name AS product_name, p.image AS product_image, o.quantity, p.price, o.order_date, o.status, pm.method AS payment_method, o.customer_message,  CONCAT(s.firstname, ' ', s.lastname) AS rider_name
          FROM `order` o 
          JOIN product p ON o.product_id = p.product_id 
          JOIN payment_method pm ON o.mop = pm.payment_id 
          LEFT JOIN staff s ON o.rider_id = s.id
          WHERE o.user_id = $userId";


$result = $conn->query($query);

// Check if the user ID is 1
if($userId == 1) {
    // Fetch orders where the user ID matches the ID in the users table
    // Fetch orders associated with the logged-in user from the database, joining with the product and payment_method tables
$query2 = "SELECT o.order_id,o.user_id,p.name AS product_name, p.image AS product_image, o.quantity, p.price, o.order_date, o.status, pm.method AS payment_method, c.firstname, c.lastname, c.middlename,  c.address AS customer_address, o.customer_message 
          FROM `order` o 
          JOIN product p ON o.product_id = p.product_id 
          JOIN payment_method pm ON o.mop = pm.payment_id
          JOIN customers c ON o.user_id = c.id 
          ";

    $result2 = $conn->query($query2);
}

?>

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
    <div class="container">
        <h1>Orders</h1>
        <hr>
        <div class="table-responsive">
             <?php
            // Check if the user ID is 1
            if($userId != 1) {
            ?>
            <h2>Orders for Logged-in User</h2>
            <table class="table table-bordered">
                <!-- Table header -->
                <thead>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Cost</th>
                        <th>Order Date</th>
                        <th>Payment Method</th>
                        <th>Rider</th>
                        <th>Customer Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            // Calculate total cost
                            $totalCost = $row['quantity'] * $row['price'];
                            echo "<tr>";
                            echo "<td>";
if ($row["tracking_number"] !== null) {
    echo $row["tracking_number"];
} else {
    echo "Wait for admin to assign a rider";
}
echo "</td>";

                           
                            echo "<td>" . $row["product_name"] . "</td>";
                            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row["product_image"]) . '" alt="Product Image" style="height: 100px;"></td>';
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td>" . $row["price"] . "</td>";
                            echo "<td>" . $totalCost . "</td>";
                            echo "<td>" . $row["order_date"] . "</td>";
                            echo "<td>" . $row["payment_method"] . "</td>";

                                         echo "<td>";
if ($row["rider_name"] !== null) {
    echo $row["rider_name"];
} else {
    echo "Wait for admin to assign a rider";
}
echo "</td>";
                            echo "<td>" . $row["customer_message"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
   echo "<td class='text-center'>";
echo "<div class='dropdown'>";
echo "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='true'>Action</button>";
echo "<div class='dropdown-menu'>";
// echo "<a class='dropdown-item' href=''>View</a>";
echo "<div class='dropdown-divider'></div>";

// Check if the status is "Delivered"
if ($row["status"] == "Delivered") {
    // Display the "Order Received" button
    echo "<a class='dropdown-item order_received' href='#' data-id='" . $row['order_id'] . "'>Order Received</a>";
}
// Check if the status is "Order Placed"
if ($row["status"] == "Order Received") {
    // Display the "Cancel Order" button
    echo "<a class='dropdown-item refund_order' href='#'>Requst Return/Refund</a>";
}

// Check if the status is "Order Placed"
            if ($row["status"] == "Order placed") {
                // Display the "Cancel Order" button with onclick event calling the JavaScript function
                echo "<a class='dropdown-item cancel_order' href='#' onclick='confirmCancelOrder(" . $row['order_id'] . ")'>Cancel Order</a>";
            }
echo "</div>"; // dropdown-menu
echo "</div>"; // dropdown
echo "</td>";


                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
             <?php } ?>
            <?php
            // Check if the user ID is 1
            if ($userId == 1) {
            ?>
            <table class="table table-bordered">
                <!-- Table header -->
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Address</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Cost</th>
                        <th>Order Date</th>
                        <th>Payment Method</th>
                        <th>Customer Message</th>
                        <th>Status</th>
                        <th>Select Rider</th> <!-- New column -->
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody>
                    <?php
                    if ($result2->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result2->fetch_assoc()) {
                            // Calculate total cost
                            $totalCost = $row['quantity'] * $row['price'];
                            echo "<tr>";
                            echo "<td>" . $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"] . "</td>";
                            echo "<td>" . $row["customer_address"] . "</td>";
                            echo "<td>";
                            echo $row["product_name"];
                            echo '<br>';
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($row["product_image"]) . '" alt="Product Image" style="height: 100px;">';
                            echo "</td>";
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td>" . $row["price"] . "</td>";
                            echo "<td>" . $totalCost . "</td>";
                            echo "<td>" . $row["order_date"] . "</td>";
                            echo "<td>" . $row["payment_method"] . "</td>";
                            echo "<td>" . $row["customer_message"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "<td>";
                            
                            // Query to select staff members from the Delivery Department
                            $queryStaff = "SELECT * FROM staff WHERE department_id = (SELECT id FROM departments WHERE name = 'Delivery Department')";
                            $resultStaff = $conn->query($queryStaff);
                            
                            // Dropdown to select a rider
                            echo "<select class='form-control' id='riderSelect'>";
                            if ($resultStaff->num_rows > 0) {
                                while ($staff = $resultStaff->fetch_assoc()) {
                                    echo "<option value='" . $staff['id'] . "'>" . $staff['firstname'] . " " . $staff['lastname'] . "</option>";
                                }
                            }
                            echo "</select>";
                            
                            echo "</td>";
                            echo "<td class='text-center'>";
                            echo "<button type='button' class='btn btn-primary btn-assign' data-order-id='" . $row['order_id'] . "'>Assign</button>";
                            echo "&nbsp;";
                            echo "<button type='button' class='btn btn-secondary btn-edit' data-id='" . $row['order_id'] . "'>Edit</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php } ?>

        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle click event for the "Assign" button
            $('.btn-assign').click(function() {
                var orderId = $(this).data('order-id');
                var riderId = $('#riderSelect').val();
                var currentDate = new Date();
                var arrivalDate = new Date(currentDate);
                arrivalDate.setDate(arrivalDate.getDate() + 3); // Add 3 days to the current date

                // Format the arrival date as yyyy-mm-dd
                var formattedArrivalDate = arrivalDate.toISOString().split('T')[0];

                // AJAX request to update the order with the generated tracking number, arrival date, and rider ID
                $.ajax({
                    url: 'update_order.php',
                    method: 'POST',
                    data: {
                        orderId: orderId,
                        arrivalDate: formattedArrivalDate,
                        riderId: riderId
                    },
                    success: function(response) {
                        alert('Order assigned successfully.');
                        // Reload the page to reflect the changes
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while assigning the order.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.order_received').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            
            // Send AJAX request to update status
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_status.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Handle response if needed
                    console.log(xhr.responseText);
                    // Reload the page or update the status in the table dynamically
                    // For simplicity, let's reload the page
                    window.location.reload();
                } else {
                    // Handle error
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Request failed');
            };
            xhr.send('order_id=' + orderId + '&status=Order Received');
        });
    });
});
</script>
<script type="">
    $(document).ready(function() {
    // Handle click event for the "Refund" button
    $('.refund_order').click(function() {
        // Redirect the user to issues.php
        window.location.href = 'index.php?page=refund';
    });

    // Handle click event for the "Assign" button (existing code)
    $('.btn-assign').click(function() {
        // Your existing code for assigning orders...
    });
});

</script>
<script>
    // Function to display confirmation dialog
    function confirmCancelOrder(orderId) {
        // Display confirmation dialog
        if (confirm("Are you sure you want to cancel this order?")) {
            // If user confirms, redirect to deleteTicketOrder.php with order ID
            window.location.href = "deleteTicketOrder.php?id=" + orderId;
        }
    }
</script>


</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
