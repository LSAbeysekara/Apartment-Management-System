<?php
include('../config.php');

$bill_id = $_POST['bill_id'];
$bill_month = $_POST['bill_month'];
$bill_type = $_POST['bill_type'];

$query = "
    SELECT 
        b.*,
        c.cus_name,
        o.$bill_type as outstanding
    FROM tbl_bill b
    LEFT JOIN tbl_customer c ON b.cus_id = c.cus_id
    LEFT JOIN tbl_outstanding o ON b.cus_id = o.cus_id
    WHERE b.bill_id = '$bill_id'
    AND b.bill_month = '$bill_month'
    AND b.bill_type = '$bill_type'
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
?>