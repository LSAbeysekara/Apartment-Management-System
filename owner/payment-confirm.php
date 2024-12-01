<?php include('../config.php'); ?>

<?php 
  if(isset($_SESSION['cus_id'])) {
    $cus_id = $_SESSION['cus_id'];
  }else{
    header('Location:../index.php');
    exit();
  }
?>

<?php

// Capture the parameters from the URL
$bill_id = isset($_GET['bill_id']) ? $_GET['bill_id'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// Validate parameters before proceeding
if (!empty($bill_id) && !empty($amount) && !empty($order_id)) {
    // Get current Date and time
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date("Y-m-d H:i:s");

    // Get the bill type using bill_id
    $sql2 = "SELECT * FROM tbl_bill WHERE bill_id='$bill_id'";
    $res2 = mysqli_query($conn, $sql2);
    $count2 = mysqli_num_rows($res2);

    if($count2>0){
        while($row=mysqli_fetch_assoc($res2)){
            $bill_type = $row['bill_type'];
        }
    }else{
        $_SESSION['form_error'] = 'Payment Error! Please again!1';
    }

    // Get outstanding amount and reduce it
    $sql4 = "SELECT `$bill_type` FROM tbl_outstanding WHERE cus_id='$cus_id'";
    $res4 = mysqli_query($conn, $sql4);

    // Check for query execution errors
    if (!$res4) {
        die("Query failed: " . mysqli_error($conn));
    }

    if ($res4 && mysqli_num_rows($res4) > 0) {
        $row = mysqli_fetch_assoc($res4);

        // Cast both values to float to perform arithmetic operations
        $bill_amount = (float) $row[$bill_type]; // Assuming $bill_type is a valid column name holding the amount
        $amount = (float) $amount; // Cast $amount to float as well

        $remaining = $bill_amount - $amount;

    } else {
        $_SESSION['form_error'] = 'Payment Error! Please try again!';
        header("Location: bill.php");
        exit();
    }



    // Prepare the SQL statement to insert into tbl_payment
    $sql = "INSERT INTO tbl_payment (paid_date, paid_by, amount, bill_id, bill_type)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $currentDateTime, $cus_id, $amount, $bill_id, $bill_type);

    if ($stmt->execute()) {
        $last_id = $conn->query("SELECT MAX(pay_id) AS last_id FROM tbl_payment")->fetch_assoc()['last_id'];
        $last_id = $last_id ? (int)substr($last_id, 4) : 0; // Extract numeric part and convert to int
        $pay_id = 'PAY' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        // Update the pay_id in the complaint table
        $sql1 = "UPDATE tbl_payment SET pay_id = '$pay_id' ORDER BY id DESC LIMIT 1";
        $res1 = mysqli_query($conn, $sql1);

        if($res1 == true){
            $sql3 = "UPDATE tbl_outstanding SET $bill_type = '$remaining' WHERE cus_id = '$cus_id'";
            $res3 = mysqli_query($conn, $sql3);

            if($res3 == true){
                $_SESSION['form_success'] = 'Payment Successful';
            }else{
                $_SESSION['form_error'] = 'Payment Error! Please again!2';
            }
        }else{
            $_SESSION['form_error'] = 'Payment Error! Please again!3';
        }
    }else{
        $_SESSION['form_error'] = 'Payment Error! Please again!4';
    }

} else {
    $_SESSION['form_error'] = 'Payment Error! Please again!5';
}

// Close the database connection
$conn->close();

header("Location: bill.php");
exit();
?>
