<?php
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['emp_username'])) {
        $id = $_POST['id'];
        $emp_name = $_POST['emp_name'];
        $emp_phone = $_POST['emp_phone'];
        $emp_email = $_POST['emp_email'];
        $emp_nic = $_POST['emp_nic'];
        $emp_username = $_POST['emp_username'];
        $emp_type = $_POST['emp_type'];
        $status = $_POST['status'];

        // Check if the username is unique
        $stmt = $conn->prepare("SELECT id FROM tbl_employee WHERE emp_username = ? AND id != ?");
        $stmt->bind_param("si", $emp_username, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            $stmt->close();
            exit();
        }

        $stmt->close();

        // Update staff details in the database
        $stmt = $conn->prepare("UPDATE tbl_employee SET emp_name = ?, emp_phone = ?, emp_email = ?, emp_nic = ?, emp_username = ?, emp_type = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $emp_name, $emp_phone, $emp_email, $emp_nic, $emp_username, $emp_type, $status, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Staff details updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update staff details.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
