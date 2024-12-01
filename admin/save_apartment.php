<?php

require('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aprt_id = $_POST['aprt_id'];
    $block = $_POST['block'];
    $floor = $_POST['floor'];
    $unit = $_POST['unit'];
    $room = $_POST['room'];
    $bathroom = $_POST['bathroom'];
    $area = $_POST['area'];
    $status = $_POST['status'];
    $ent_by = $_SESSION['staffname']; // Assuming staffname is the logged-in user

    $query = "INSERT INTO tbl_apartment (aprt_id, block_no, floor_no, aprt_no, room_count, bathroom_count, area, ent_by, status)
              VALUES ('$aprt_id', '$block', '$floor', '$unit', '$room', '$bathroom', '$area', '$ent_by', '$status')";

    if ($conn->query($query) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
