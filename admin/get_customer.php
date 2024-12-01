<?php
include '../config.php'; // Your database connection script

$aprt_id = $_GET['aprt_id'];

$sql = "SELECT cus_name, cus_id FROM tbl_customer WHERE apartment_id = '$aprt_id'";
$result = $conn->query($sql);

$customer = $result->fetch_assoc();
echo json_encode($customer);
?>
