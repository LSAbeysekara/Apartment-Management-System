<?php
include('../config.php');

if (isset($_POST['bill_id'])) {
    $bill_id = $_POST['bill_id'];
    error_log("Received bill_id: " . $bill_id); // Debugging line

    // Query to get the bill details based on bill_id
    $query = "SELECT * FROM tbl_bill WHERE bill_id = ?";
    $stmt = $conn ->prepare($query);

    if (!$stmt) {
        error_log("SQL prepare error: " . $conn->error);
        echo json_encode(['error' => 'Database error']);
        exit();
    }

    $stmt->bind_param("i", $bill_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bill = $result->fetch_assoc();
        // Return the bill details as JSON
        echo json_encode($bill);
    } else {
        echo json_encode(['error' => 'No bill found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>