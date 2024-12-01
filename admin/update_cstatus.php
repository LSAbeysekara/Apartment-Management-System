<?php
require('../config.php');
$staff=$_SESSION['staffname'];
$data = json_decode(file_get_contents('php://input'), true);
$requestId = $data['id'];
$newStatus = $data['status'];
$cunndate= date("Y-m-d H:i:s");
if ($requestId && $newStatus) {
    $updateQuery = "UPDATE tbl_complaint SET status = ?, app_date=?, app_by=? WHERE com_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssss", $newStatus, $cunndate, $staff, $requestId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
