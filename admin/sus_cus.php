<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a_id = $_POST['id'];

    include('../config.php');
    $cdate=date('Y-m-d');
    $sql = "UPDATE `tbl_aptenroll` SET `end_date`='$cdate' WHERE `apt_id` =  '$a_id'";
    $sql2 = "UPDATE `tbl_apartment` SET `cus_id`='',`status`='open' WHERE `aprt_id`=  '$a_id'";
    $sql3 = "UPDATE `tbl_customer` SET `status`='End' WHERE `apartment_id`=  '$a_id'";
    if ($conn->query($sql) === TRUE) {
        $conn->query($sql2);
        $conn->query($sql3);
        $response = array('success' => true);
    } else {
        $response = array('success' => false, 'error' => $conn->error);
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array('success' => false, 'error' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>