<?php
require('../config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare and execute query to fetch staff details by ID
    $stmt = $conn->prepare("SELECT emp_id, emp_name, emp_phone, emp_email, emp_nic, emp_type, status, ent_date, ent_by FROM tbl_employee WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);  // Send staff details as JSON
    } else {
        echo json_encode(['error' => 'No record found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
$conn->close();
?>
