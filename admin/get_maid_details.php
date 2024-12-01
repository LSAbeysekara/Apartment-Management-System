<?php
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    // Add error logging to debug the received ID
    error_log("Received ID: " . $id);
    
    // Modified query with explicit column selection and proper joins
    $query = "SELECT *
    FROM tbl_maid a 
    LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id 
    WHERE a.m_id = ?";
    
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
            'm_id' => htmlspecialchars($row['m_id']),
            'aprt_id' => htmlspecialchars($row['aprt_id']),
            'm_name' => htmlspecialchars($row['m_name']),
            'm_nic' => htmlspecialchars($row['m_nic']),
            'm_phone' => htmlspecialchars($row['m_phone']),
            'm_address' => htmlspecialchars($row['m_address']),
            'gender' => htmlspecialchars($row['gender']),
            'blood_type' => htmlspecialchars($row['blood_type']),
            'dob' => htmlspecialchars($row['dob']),
            'mother_name' => htmlspecialchars($row['mother_name']),
            'father_name' => htmlspecialchars($row['father_name']),
            'app_date' => htmlspecialchars($row['app_date']),
            'req_date' => htmlspecialchars($row['req_date']),
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