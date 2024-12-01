<?php
include('../config.php');

if (isset($_POST['cus_id']) && isset($_POST['bill_type'])) {
    $cus_id = $_POST['cus_id'];
    $bill_type = $_POST['bill_type'];

    // Check if the bill type is Other
    if ($bill_type === 'Other') {
        // Prepare the SQL query to mark the request as Denied
        $stmt = $conn->prepare("UPDATE tbl_customer SET Other = 'No' WHERE cus_id = ?");
        $stmt->bind_param("s", $cus_id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Request denied successfully.']);
        } else {
            echo json_encode(['message' => 'Error denying the request.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['message' => 'Invalid bill type.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
