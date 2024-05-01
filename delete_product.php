<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Perform deletion operation in the database
    $deleteQuery = "DELETE FROM product WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }

    mysqli_stmt_close($stmt);
} else {
    echo 'error';
}

mysqli_close($conn);
?>
