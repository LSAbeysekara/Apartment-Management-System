<?php
include('../config.php');

$bills = $_POST['bills'];
$crt_by = $_SESSION['staffname'];
$bill_type = 'Gym'; // Specify bill type
$outstanding_column = $bill_type; // Use bill type as column name

foreach ($bills as $bill) {
    $cus_id = $bill['cus_id'];
    $amount = $bill['amount'];
    $message = $bill['message'];
    $bill_month = $bill['bill_month'];
    $aprt_id = $bill['aprt_id'];

    // Check for existing bill of the same type and month
    $check_existing = "SELECT bill_id FROM tbl_bill 
                      WHERE cus_id = '$cus_id' 
                      AND bill_month = '$bill_month' 
                      AND bill_type = '$bill_type'";
    $result = $conn->query($check_existing);
    
    if ($result->num_rows > 0) {
        continue; // Skip if bill already exists
    }

    // Generate unique bill number
    $sql_bill = "SELECT MAX(CAST(SUBSTRING(bill_id, 2) AS UNSIGNED)) AS max_bill_id FROM tbl_bill";
    $result_bill = $conn->query($sql_bill);
    $row_bill = $result_bill->fetch_assoc();
    $next_bill_id = 'B' . str_pad(($row_bill['max_bill_id'] + 1), 4, '0', STR_PAD_LEFT);

    // Insert new bill
    $sql = "INSERT INTO tbl_bill (
                bill_id, cus_id, aprt_id, amount, message, bill_month, 
                bill_type, req_date, crt_by, crt_date
            ) VALUES (
                '$next_bill_id', '$cus_id', '$aprt_id', '$amount', '$message', 
                '$bill_month', '$bill_type', NOW(), '$crt_by', NOW()
            )";

    if ($conn->query($sql) === TRUE) {
        // Check for outstanding record
        $outstanding_check = "SELECT $outstanding_column 
                            FROM tbl_outstanding 
                            WHERE cus_id = '$cus_id'";
        $result = $conn->query($outstanding_check);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_outstanding = $row[$outstanding_column] + $amount;

            $update_outstanding = "UPDATE tbl_outstanding 
                                 SET $outstanding_column = '$new_outstanding' 
                                 WHERE cus_id = '$cus_id'";
            $conn->query($update_outstanding);
        } else {
            // Generate new outstanding ID
            $sql_outstanding = "SELECT MAX(CAST(SUBSTRING(out_id, 2) AS UNSIGNED)) AS max_out_id FROM tbl_outstanding";
            $result_outstanding = $conn->query($sql_outstanding);
            $row_outstanding = $result_outstanding->fetch_assoc();
            $next_out_id = 'O' . str_pad(($row_outstanding['max_out_id'] + 1), 4, '0', STR_PAD_LEFT);

            // Insert new outstanding record
            $insert_outstanding = "INSERT INTO tbl_outstanding (out_id, cus_id, $outstanding_column) 
                                 VALUES ('$next_out_id', '$cus_id', '$amount')";
            $conn->query($insert_outstanding);
        }
    }
}

echo json_encode(['message' => 'All bills and outstanding amounts updated successfully']);
?>