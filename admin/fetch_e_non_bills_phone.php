<?php
include('../config.php');

$sql = "SELECT 
            c.cus_id, 
            c.cus_name, 
            c.apartment_id AS aprt_id 
        FROM 
            tbl_customer AS c
        WHERE 
            c.status = 'Active' 
            AND (c.Telephone IS NULL OR c.Telephone = '');"; // Check if Telephone is NULL or an empty string

$result = $conn->query($sql);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
