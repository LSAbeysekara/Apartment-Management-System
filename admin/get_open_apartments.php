<?php
require('../config.php');
$query = "SELECT id, aprt_id FROM tbl_apartment WHERE status = 'open'";
$result = $conn->query($query);
$apartments = [];
while ($row = $result->fetch_assoc()) {
    $apartments[] = $row;
}
echo json_encode($apartments);
?>
