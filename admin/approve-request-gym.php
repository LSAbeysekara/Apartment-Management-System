<?php
include('../config.php');

if (isset($_POST['cus_id']) && isset($_POST['bill_type'])) {
    $cus_id = $_POST['cus_id'];
    $bill_type = $_POST['bill_type'];

    // Check if the bill type is Gym
    if ($bill_type === 'Gym') {
        $stmt = $conn->prepare("UPDATE tbl_customer SET Gym = 'Yes' WHERE cus_id = ?");
        $stmt->bind_param("s", $cus_id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Request approved successfully.']);
        } else {
            echo json_encode(['message' => 'Error approving the request.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['message' => 'Invalid bill type.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
