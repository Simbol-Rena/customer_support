<?php
include 'db_connect.php';

// Check if POST data exists
if (isset($_POST['order_id'], $_POST['status'])) {
    // Sanitize and validate input
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    // Update status in database
    $updateQuery = "UPDATE `order` SET status = '$status' WHERE order_id = $orderId";
    if ($conn->query($updateQuery) === TRUE) {
         header('Location: index.php?page=delivery_list');
        exit;
    } else {
        echo 'Error updating status: ' . $conn->error;
    }

    // Handle uploaded image if it exists
    if ($_FILES['proof_image']['error'] === 0) {
        $targetDir = "proof_images/"; // Directory to store uploaded images
        $targetFile = $targetDir . basename($_FILES["proof_image"]["name"]);
        
        // Check if the file already exists
        if (file_exists($targetFile)) {
             header('Location: index.php?page=delivery_list');
        exit;
        } else {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $targetFile)) {
                // Update delivery_proof_image in database
                $updateImageQuery = "UPDATE `order` SET delivery_proof_image = '$targetFile' WHERE order_id = $orderId";
                if ($conn->query($updateImageQuery) === TRUE) {
                     header('Location: index.php?page=delivery_list');
        exit;
                } else {
                     header('Location: index.php?page=delivery_list');
        exit;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded";
    }
} else {
    echo 'Invalid request';
}
$conn->close();
?>
