<?php
include('../config.php'); 

if (!isset($_SESSION['cus_id'])) {
    header('Location: ../index.php');
    exit();
}

$cus_id = $_SESSION['cus_id']; // Assuming customer ID is stored in session

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = $_POST['request_type'];
    $req = "request";

    $sql = "UPDATE tbl_customer SET $request_type = ? WHERE cus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $req, $cus_id);

    if ($stmt->execute()) {
        $_SESSION['form_success'] = 'Successfully Requested!';
        echo "<script>window.location.href='bill.php';</script>";
    } else {
        $_SESSION['form_error'] = 'Failed to Request! Try again!';
        echo "<script>window.location.href='bill.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
