<?php
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $block = $_POST['block'];
    $floor = $_POST['floor'];
    $unit = $_POST['unit'];
    $room = $_POST['room'];
    $bathroom = $_POST['bathroom'];
    $area = $_POST['area'];
    $status = $_POST['status'];

    // Update query
    $query = "UPDATE tbl_apartment SET block_no = ?, floor_no = ?, aprt_no = ?, room_count = ?, bathroom_count = ?, area = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiissi", $block, $floor, $unit, $room, $bathroom, $area, $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update apartment details']);
    }
}
?>
