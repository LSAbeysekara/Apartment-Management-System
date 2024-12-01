<?php
include('../config.php'); // Include your database configuration

if (isset($_SESSION['cus_id'])) {
    $cus_id = $_SESSION['cus_id'];
} else {
    header('Location:../index.php');
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $currentPassword = $_POST['cpassword'];
    $newPassword = $_POST['npassword'];
    $confirmPassword = $_POST['conpassword'];

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        $_SESSION['form_error'] = 'New passwords do not match!';
        echo "<script>window.location.href='profile.php';</script>";
        exit();
    }

    // Fetch the user's current password hash from the database
    $sql = "SELECT cus_password FROM tbl_customer WHERE cus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cus_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password (assuming the stored password is also hashed with SHA-1)
    if (sha1($currentPassword) !== $hashedPassword) {
        $_SESSION['form_error'] = 'Current password is incorrect!';
        echo "<script>window.location.href='profile.php';</script>";
        exit();
    }

    // Hash the new password with SHA-1
    $newHashedPassword = sha1($newPassword);

    // Update the password in the database
    $updateSql = "UPDATE tbl_customer SET cus_password = ? WHERE cus_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $newHashedPassword, $cus_id);

    if ($updateStmt->execute()) {
        $_SESSION['form_success'] = 'Password changed successfully!';
        echo "<script>window.location.href='profile.php';</script>";
    } else {
        $_SESSION['form_error'] = 'Failed to change the password!';
        echo "<script>window.location.href='profile.php';</script>";
    }

    $updateStmt->close();
    $conn->close();
}
?>
