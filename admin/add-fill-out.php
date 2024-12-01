<?php
include('../config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $nic = htmlspecialchars(trim($_POST['nic']));
    $startDate = $_POST['s_date'];
    $message = $_POST['message'];
    $endDate = $_POST['e_date'];
    $person_count = $_POST['p_count'];
    $aprt_id = $_POST['aprt_id'];
    $status = $_POST['status'];
    $cus_id= $_POST['cus_id'];

    // Get current Date and time
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date("Y-m-d H:i:s");

    // Basic validation
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $_SESSION['form_error'] = 'Invalid phone number format.';
        header("Location: fill-out.php"); // Redirect to form page with error
        exit();
    }
    if (strtotime($endDate) < strtotime($startDate)) {
        $_SESSION['form_error'] = 'End date must be after the start date.';
        header("Location: fill-out.php");
        exit();
    }

    // Insert into the database (example query, adjust according to your table structure)
    $sql = "INSERT INTO tbl_fillout (rq_date, cus_id, aprt_id, message, contr_name, contr_phone, fillout_st_date, fillout_end_date, pass_count, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $currentDateTime, $cus_id, $aprt_id, $message, $name, $phone, $startDate, $endDate, $person_count, $status);

    if ($stmt->execute()) {

        $last_id = $conn->query("SELECT MAX(fo_id) AS last_id FROM tbl_fillout")->fetch_assoc()['last_id'];
        $last_id = $last_id ? (int)substr($last_id, 4) : 0;
        $fo_id = 'FO' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        // Update the ent_id in the complaint table
        $sql1 = "UPDATE tbl_fillout SET fo_id = '$fo_id' ORDER BY id DESC LIMIT 1";
        $res1 = mysqli_query($conn, $sql1);

        if($res1 == true){
            $_SESSION['form_success'] = 'Applied successfully!';
        }else{
            $_SESSION['form_error'] = 'Error updating the database.';
        }
    } else {
        $_SESSION['form_error'] = 'Error submitting the form.';
    } 

    $stmt->close();
    $conn->close();

    // Redirect back with success or error message
    header("Location: new-fill-out.php");
    exit();
}
?>
