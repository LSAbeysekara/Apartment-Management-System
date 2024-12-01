<?php
include('../config.php');

if (isset($_POST['cus_id']) && isset($_POST['bill_type'])) {
    // Use prepared statements to prevent SQL injection
    $cus_id = $_POST['cus_id'];
    $bill_type = $_POST['bill_type'];

    // Check if the bill type is "Telephone" before proceeding
    if ($bill_type === 'Telephone') {
        // Prepare the SQL query
        $stmt = $conn->prepare("UPDATE tbl_customer SET Telephone = 'Yes' WHERE cus_id = ?");
        $stmt->bind_param("s", $cus_id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Customer added to Telephone bill type successfully.']);
        } else {
            echo json_encode(['message' => 'Error adding customer to Telephone bill type.']);
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo json_encode(['message' => 'Invalid bill type.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
