<?php

include '../config.php'; // Include your database connection file here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = $_SESSION['staffid']; // Assuming emp_id is stored in the session after login
    $cpassword = sha1($_POST['cpassword']);
    $npassword = sha1($_POST['npassword']);
    $confirm_npassword = sha1($_POST['confirm_npassword']);

    // Check if the new password and confirm password match
    if ($npassword !== $confirm_npassword) {
        echo json_encode(['status' => 'error', 'message' => 'New passwords do not match.']);
        exit;
    }

    // Verify the current password
    $query = "SELECT emp_password FROM tbl_employee WHERE emp_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if ($stored_password !== $cpassword) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
        exit;
    }

    // Update the password
    $update_query = "UPDATE tbl_employee SET emp_password = ? WHERE emp_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $npassword, $emp_id);

    if ($update_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password changed successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to change password.']);
    }

    $update_stmt->close();
    $conn->close();
}
?>
