<?php
include('../config.php');

$currentMonth = date('F');
$currentYear = date('Y');
$currentMonthYear = "$currentMonth $currentYear";
$billType = 'Other'; // Specify bill type

// Updated SQL query to include bill type check
$sql = "SELECT 
    c.cus_id, 
    c.cus_name, 
    c.apartment_id AS aprt_id, 
    o.Gym AS outstanding, 
    b.bill_id,
    b.bill_month, 
    b.amount, 
    b.message, 
    b.bill_type
FROM 
    tbl_customer AS c
LEFT JOIN 
    tbl_outstanding AS o ON c.cus_id = o.cus_id
LEFT JOIN 
    tbl_bill AS b ON c.cus_id = b.cus_id 
    AND b.bill_month = '$currentMonthYear'
    AND b.bill_type = '$billType'
WHERE 
    c.Other = 'request' 
    AND c.status = 'Active';";

$result = $conn->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>