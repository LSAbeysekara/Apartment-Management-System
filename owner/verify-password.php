<?php

include('../config.php');

if (isset($_SESSION['cus_id'])) {
    $cus_id = $_SESSION['cus_id'];
}else{
    header('Location:../index.php');
    exit();
}

// Retrieve JSON data from request
$data = json_decode(file_get_contents("php://input"), true);
$currentPassword = $data['currentPassword'] ?? '';

// Fetch the user's current password hash from the database
$sql = "SELECT cus_password FROM tbl_customer WHERE cus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cus_id);
$stmt->execute();
$stmt->bind_result($storedHashedPassword);
$stmt->fetch();
$stmt->close();

$response = ['success' => false];

// Convert input password to SHA-1 and verify it against the stored hash
$hashedInputPassword = sha1($currentPassword);

if ($hashedInputPassword === $storedHashedPassword) {
    $response['success'] = true;
}

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);

$conn->close();

?>
