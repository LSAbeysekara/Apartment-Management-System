<?php
require('../config.php');

$id = $_POST['id'];
$cus_name = $_POST['cus_name'];
$cus_gender = $_POST['cus_gender'];
$cus_nic = $_POST['cus_nic'];
$cus_email = $_POST['cus_email'];
$cus_phone = $_POST['cus_phone'];
$apartment_id = $_POST['apartment_id'];

// Update query
$query = "UPDATE tbl_customer SET cus_name = ?, cus_gender = ?, cus_nic = ?, cus_email = ?, cus_phone = ?, apartment_id = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssi", $cus_name, $cus_gender, $cus_nic, $cus_email, $cus_phone, $apartment_id, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User details updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating user details.']);
}
?>
