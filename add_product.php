<?php

// Include database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present
    if (isset($_POST['productName'], $_POST['productInfo'], $_POST['productQuantity'], $_POST['productPrice'], $_POST['productExpirationDate'])) {
        // Sanitize input data to prevent SQL injection (consider prepared statements)
        $productName = mysqli_real_escape_string($conn, $_POST['productName']);
        $productInfo = mysqli_real_escape_string($conn, $_POST['productInfo']);
        $productQuantity = mysqli_real_escape_string($conn, $_POST['productQuantity']);
        $productPrice = mysqli_real_escape_string($conn, $_POST['productPrice']);
        $productExpirationDate = mysqli_real_escape_string($conn, $_POST['productExpirationDate']);

        // File upload handling
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
            // Define the destination directory
            $uploadDir = 'uploads/';

            // Get the uploaded file data
            $imageTmpName = $_FILES['productImage']['tmp_name'];
            $imageType = $_FILES['productImage']['type'];
            $imageFileName = $_FILES['productImage']['name'];

            // Check if the uploaded file is an image
            if (strpos($imageType, 'image') !== false) {
                // Move the uploaded image file to the destination directory
                if (move_uploaded_file($imageTmpName, $uploadDir . $imageFileName)) {
                    // Read the image data into a variable
                    $imageData = file_get_contents($uploadDir . $imageFileName);

                    // Insert the new product into the product table along with the image data
                    $insertQuery = "INSERT INTO product (name, info, quantity, price, expiration_date, image) VALUES (?, ?, ?, ?, ?, ?)";

                    // Prepare the SQL statement
                    $stmt = mysqli_prepare($conn, $insertQuery);

                    // Bind parameters to the statement
                    mysqli_stmt_bind_param($stmt, "ssidsb", $productName, $productInfo, $productQuantity, $productPrice, $productExpirationDate, $imageData);

                    // Execute the statement
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) {
                        // Return success response
                        echo 'success';
                    } else {
                        // Return error response
                        echo 'error';
                    }

                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    // Error: Failed to move uploaded file
                    echo 'Error: Failed to move uploaded file.';
                }
            } else {
                // Error: File is not an image
                echo 'Error: File is not an image.';
            }
        } else {
            // Error: No file uploaded or file upload error occurred
            echo 'Error uploading file: ' . $_FILES['productImage']['error'];
        }
    } else {
        // Return error response if required fields are missing
        echo 'error';
    }
} else {
    // Return error response for non-POST requests
    echo 'error';
}

// Close database connection
mysqli_close($conn);

?>
