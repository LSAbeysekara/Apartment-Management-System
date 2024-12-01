<?php
include '../config.php'; // Your database connection script

$sql = "SELECT aprt_id FROM tbl_apartment WHERE status = 'Occupied'";
$result = $conn->query($sql);

$apartments = [];
while ($row = $result->fetch_assoc()) {
    $apartments[] = $row;
}

echo json_encode($apartments);
?>
