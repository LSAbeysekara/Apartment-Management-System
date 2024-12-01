<?php
include('../config.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form inputs
    $vehicle_number = htmlspecialchars(trim($_POST['v_number']));
    $vehicle_color = htmlspecialchars(trim($_POST['v_color']));
    $vehicle_type = htmlspecialchars(trim($_POST['v_type']));
    $aprt_id = htmlspecialchars(trim($_POST['aprt_id']));
    $status = $_POST['status'];
    $cus_id=$_POST['cus_id'];
    $p_spot = htmlspecialchars(trim($_POST['p_spot']));
    $date=date("Y-m-d H:i:s");
    $app_by=$_SESSION['staffname'];


    // Get current Date and time
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date("Y-m-d H:i:s");

    // Basic validation
    if (empty($vehicle_number) || empty($vehicle_color) || empty($vehicle_type)) {
        $_SESSION['form_error'] = 'Please fill in all fields.';
        header("Location: new-vehicle-pass.php");
        exit();
    }

    // Insert into the database (example query, adjust according to your table structure)
    $sql = "INSERT INTO tbl_vehicle (req_date, cus_id, aprt_id, v_number, v_type, v_color, status,p_spot,app_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $currentDateTime, $cus_id, $aprt_id, $vehicle_number, $vehicle_type, $vehicle_color, $status,$p_spot,$date,$app_by);

    if ($stmt->execute()) {
        // Generate the vehicle pass ID
        $last_id = $conn->query("SELECT MAX(v_id) AS last_id FROM tbl_vehicle")->fetch_assoc()['last_id'];
        $last_id = $last_id ? (int)substr($last_id, 4) : 0;
        $v_id = 'V' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        // Update the pass_id in the table
        $sql1 = "UPDATE tbl_vehicle SET v_id = '$v_id' ORDER BY id DESC LIMIT 1";
        $res1 = mysqli_query($conn, $sql1);

        if ($res1) {
            $_SESSION['form_success'] = 'Vehicle pass applied successfully!';
        } else {
            $_SESSION['form_error'] = 'Error updating the database.';
        }
    } else {
        $_SESSION['form_error'] = 'Error submitting the form.';
    }

    $stmt->close();
    $conn->close();

    // Redirect back with success or error message
    header("Location: new-vehicle-pass.php");
    exit();
}
?>
