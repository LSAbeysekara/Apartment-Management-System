<?php
require('../config.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    // Add error logging to debug the received ID
    error_log("Received ID: " . $id);
    
    // Modified query with explicit column selection and proper joins
    $query = "SELECT * 
    FROM tbl_fillout a 
    LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id 
    WHERE a.fo_id = ?";
    
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
            'fo_id' => htmlspecialchars($row['fo_id']),
            'aprt_id' => htmlspecialchars($row['aprt_id']),
            'contr_name' => htmlspecialchars($row['contr_name']),
            'contr_phone' => htmlspecialchars($row['contr_phone']),
            'message' => htmlspecialchars($row['message']),
            'pass_count' => htmlspecialchars($row['pass_count']),
            'rq_date' => htmlspecialchars($row['rq_date']),
            'fillout_st_date' => htmlspecialchars($row['fillout_st_date']),
            'fillout_end_date' => htmlspecialchars($row['fillout_end_date']),
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