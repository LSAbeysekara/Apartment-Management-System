<?php
include('../config.php');

// Approve all requests for the Electricity bill type
$sql = "UPDATE tbl_customer SET Gas = 'Yes' WHERE Gas = 'request' AND status = 'Active'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'All requests have been approved.']);
} else {
    echo json_encode(['message' => 'Error approving all requests.']);
}
?>
