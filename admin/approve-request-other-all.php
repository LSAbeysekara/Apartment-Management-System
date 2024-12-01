<?php
include('../config.php');

// Approve all requests for the Gym bill type
$sql = "UPDATE tbl_customer SET Other = 'Yes' WHERE Other = 'request' AND status = 'Active'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'All requests have been approved.']);
} else {
    echo json_encode(['message' => 'Error approving all requests.']);
}
?>
