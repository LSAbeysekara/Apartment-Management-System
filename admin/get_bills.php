<?php
// get_electricity_bills.php
include('../config.php');

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('F');
$bill_type = $_GET['bill_type'];

$query = "
    SELECT 
        b.bill_id,
        b.cus_id,
        b.aprt_id,
        c.cus_name,
        b.bill_month,
        b.amount,
        b.message,
        o.$bill_type as outstanding
    FROM tbl_bill b
    LEFT JOIN tbl_customer c ON b.cus_id = c.cus_id
    LEFT JOIN tbl_outstanding o ON b.cus_id = o.cus_id
    WHERE b.bill_type = '$bill_type'
    AND b.bill_month LIKE '%$month $year%'
    ORDER BY b.crt_date DESC
";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    'data' => $data
]);


