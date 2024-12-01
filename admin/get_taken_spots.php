<?php
include('../config.php');

$query = "SELECT p_spot FROM tbl_vehicle WHERE p_spot IS NOT NULL";
$result = $conn->query($query);

$takenSpots = [];
while ($row = $result->fetch_assoc()) {
    $takenSpots[] = $row['p_spot'];
}

echo json_encode($takenSpots);
?>
