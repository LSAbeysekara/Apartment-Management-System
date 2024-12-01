<?php
require('../config.php');

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Set status based on action
    $status = ($action === 'deactivate') ? 'Inactive' : 'Active';

    // Prepare and execute the update query to change the status
    $stmt = $conn->prepare("UPDATE tbl_employee SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Staff member has been successfully {$status}."]);
    } else {
        echo json_encode(['success' => false, 'message' => "Failed to update staff status."]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
