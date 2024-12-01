<?php
include('../config.php');

$cus_id = $_POST['cus_id'];
$amount = $_POST['amount'];
$message = $_POST['message'];
$bill_month = $_POST['bill_month'];
$aprt_id = $_POST['aprt_id'];
$bill_type = 'Telephone'; // Specify bill type
$outstanding_column = $bill_type; // Use bill type as column name
$crt_by = $_SESSION['staffname'];

// Check if it's an edit action
if (isset($_POST['edit_bill_id'])) {
    $edit_bill_id = $_POST['edit_bill_id'];
    $current_amount = $_POST['current_amount'];

    // Get current outstanding value for specific bill type
    $sql_outstanding = "SELECT $outstanding_column FROM tbl_outstanding WHERE cus_id = '$cus_id'";
    $result_outstanding = $conn->query($sql_outstanding);
    $row_outstanding = $result_outstanding->fetch_assoc();
    $current_outstanding = $row_outstanding[$outstanding_column];
    
    // Calculate new outstanding
    $new_outstanding = $current_outstanding - $current_amount + $amount;

    // Update the bill
    $sql_update_bill = "UPDATE tbl_bill 
                        SET amount = '$amount', 
                            message = '$message', 
                            crt_by = '$crt_by', 
                            crt_date = NOW() 
                        WHERE bill_id = '$edit_bill_id' 
                        AND bill_type = '$bill_type'";

    if ($conn->query($sql_update_bill) === TRUE) {
        // Update the outstanding value for specific bill type
        $update_outstanding = "UPDATE tbl_outstanding 
                             SET $outstanding_column = '$new_outstanding' 
                             WHERE cus_id = '$cus_id'";
        
        if ($conn->query($update_outstanding) === TRUE) {
            echo json_encode(['message' => 'Bill and outstanding updated successfully']);
        } else {
            echo json_encode(['message' => 'Error updating outstanding: ' . $conn->error]);
        }
    } else {
        echo json_encode(['message' => 'Error updating bill: ' . $conn->error]);
    }
} else {
    // Check for existing bill of the same type and month
    $check_existing = "SELECT bill_id FROM tbl_bill 
                      WHERE cus_id = '$cus_id' 
                      AND bill_month = '$bill_month' 
                      AND bill_type = '$bill_type'";
    $result = $conn->query($check_existing);
    
    if ($result->num_rows > 0) {
        echo json_encode(['message' => 'Bill already exists for this month and type']);
        exit;
    }

    // Handle new bill creation
    $sql_bill = "SELECT MAX(CAST(SUBSTRING(bill_id, 2) AS UNSIGNED)) AS max_bill_id FROM tbl_bill";
    $result_bill = $conn->query($sql_bill);
    $row_bill = $result_bill->fetch_assoc();
    $next_bill_id = 'B' . str_pad(($row_bill['max_bill_id'] + 1), 4, '0', STR_PAD_LEFT);

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

            // Insert new outstanding record with correct column
            $insert_outstanding = "INSERT INTO tbl_outstanding (out_id, cus_id, $outstanding_column) 
                                 VALUES ('$next_out_id', '$cus_id', '$amount')";
            $conn->query($insert_outstanding);
        }

        echo json_encode(['message' => 'Bill and outstanding updated successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}
?>