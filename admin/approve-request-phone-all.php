<?php
include('../config.php');

// Approve all requests for the Telephone bill type
$sql = "UPDATE tbl_customer SET Telephone = 'Yes' WHERE Telephone = 'request' AND status = 'Active'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'All requests have been approved.']);
} else {
    echo json_encode(['message' => 'Error approving all requests.']);
}
?>