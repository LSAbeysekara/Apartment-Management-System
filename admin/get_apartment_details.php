<?php
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $query = "SELECT a.aprt_id, a.block_no, a.floor_no, a.aprt_no, a.room_count, a.bathroom_count, a.area, a.status, c.cus_name 
              FROM tbl_apartment a 
              LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id 
              WHERE a.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row['cus_name'] = $row['cus_name'] ?? ''; // Handle if cus_name is null
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
}
?>
