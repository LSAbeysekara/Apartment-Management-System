<?php
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    // Add error logging to debug the received ID
    error_log("Received ID: " . $id);
    
    // Modified query with explicit column selection and proper joins
    $query = "SELECT 
        a.ent_id,
        a.aprt_id,
        a.ent_name,
        a.ent_phone,
        a.ent_nic,
        a.person_count,
        a.req_date,
        a.st_date,
        a.end_date,
        a.status,
        c.cus_name 
    FROM tbl_entrypass a 
    LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id 
    WHERE a.ent_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id); // Changed to "s" if ent_id is varchar
    
    // Add error logging for query execution
    if (!$stmt->execute()) {
        error_log("Query execution failed: " . $stmt->error);
        echo json_encode(['error' => 'Query execution failed']);
        exit;
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Sanitize the output
        $data = array(
            'ent_id' => htmlspecialchars($row['ent_id']),
            'aprt_id' => htmlspecialchars($row['aprt_id']),
            'ent_name' => htmlspecialchars($row['ent_name']),
            'ent_phone' => htmlspecialchars($row['ent_phone']),
            'ent_nic' => htmlspecialchars($row['ent_nic']),
            'person_count' => htmlspecialchars($row['person_count']),
            'req_date' => htmlspecialchars($row['req_date']),
            'st_date' => htmlspecialchars($row['st_date']),
            'end_date' => htmlspecialchars($row['end_date']),
            'status' => htmlspecialchars($row['status']),
            'cus_name' => htmlspecialchars($row['cus_name'] ?? 'N/A')
        );
        echo json_encode($data);
    } else {
        error_log("No data found for ID: " . $id);
        echo json_encode(['error' => 'No data found']);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>