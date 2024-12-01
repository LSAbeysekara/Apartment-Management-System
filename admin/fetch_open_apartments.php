<?php
require('../config.php');

$query = "SELECT aprt_id, block_no, aprt_no,floor_no FROM tbl_apartment WHERE status = 'open'";
$result = $conn->query($query);
$apartments = [];

while ($row = $result->fetch_assoc()) {
    $apartments[] = $row;
}

echo json_encode($apartments);
?>
