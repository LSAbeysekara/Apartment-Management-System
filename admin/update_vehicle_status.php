<?php
// update_entry_status.php
include('../config.php');

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Add error logging
error_log("Update request received - ID: " . ($_POST['id'] ?? 'none') . ", Status: " . ($_POST['status'] ?? 'none'));

// Validate input parameters
if (!isset($_POST['v_id']) || !isset($_POST['status'])) {
    $response['message'] = 'Missing required parameters';
    echo json_encode($response);
    exit;
}

// Sanitize and validate inputs
$v_id = filter_var($_POST['v_id'], FILTER_SANITIZE_STRING);
$status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
$staff = $_SESSION['staffname'] ?? 'Unknown';
$p_spot = $_POST['p_spot'] ?? null;

// Validate entry exists before update
$checkQuery = "SELECT v_id, status FROM tbl_vehicle WHERE v_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $v_id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    $response['message'] = 'Entry pass not found';
    echo json_encode($response);
    $checkStmt->close();
    exit;
}

// Get current status
$currentRecord = $result->fetch_assoc();
if ($currentRecord['status'] !== 'Pending') {
    $response['message'] = 'Can only update pending entry passes';
    echo json_encode($response);
    $checkStmt->close();
    exit;
}

// Prepare and execute update
try {
    // If parking spot is provided, include it in the query
    if ($p_spot) {
        $query = "UPDATE tbl_vehicle 
                  SET status = ?, 
                      app_date = NOW(),
                      app_by = ?,
                      p_spot = ?
                  WHERE v_id = ? 
                  AND status = 'Pending'";  // Only update if status is pending
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $status, $staff, $p_spot, $v_id);
    } else {
        // If parking spot is not provided, exclude it from the query
        $query = "UPDATE tbl_vehicle 
                  SET status = ?, 
                      app_date = NOW(),
                      app_by = ?
                  WHERE v_id = ? 
                  AND status = 'Pending'";  // Only update if status is pending
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $status, $staff, $v_id);
    }

    if ($stmt->execute()) {
        // Check if any rows were actually updated
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = "Vehicle pass {$v_id} has been {$status}";
            
            // Log the successful update
            error_log("Successfully updated Vehicle pass {$v_id} to {$status} by {$staff}");
        } else {
            $response['message'] = 'No changes made - Vehicle pass may already be processed';
            error_log("No rows updated for Vehicle pass {$v_id} - possible concurrent update");
        }
    } else {
        $response['message'] = 'Database update failed';
        error_log("Update failed for Vehicle pass {$v_id}: " . $stmt->error);
    }
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'System error occurred';
    error_log("Exception during update: " . $e->getMessage());
}

$conn->close();
echo json_encode($response);
?>
