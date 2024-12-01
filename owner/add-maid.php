<?php
include('../config.php');

if(isset($_SESSION['cus_id'])){
    $cus_id = $_SESSION['cus_id'];
}else{
    header('Location:../index.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $father = htmlspecialchars(trim($_POST['father']));
    $mother = htmlspecialchars(trim($_POST['mother']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $dob = $_POST['dob'];
    $nic = htmlspecialchars(trim($_POST['nic']));
    $gender = htmlspecialchars(trim($_POST['gender'])); 
    $blood_type = htmlspecialchars(trim($_POST['blood_type']));
    $address = htmlspecialchars(trim($_POST['address']));
    $status = 'Pending';
    $aprt_id = $_POST['aprt_id'];

    // Get current Date and time
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date("Y-m-d H:i:s");

    // Basic validation
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $_SESSION['form_error'] = 'Invalid phone number format.';
        header("Location: maid-request.php"); // Redirect to form page with error
        exit();
    }
    if (strtotime($dob) > strtotime('-18 years')) { // Check for age 18+
        $_SESSION['form_error'] = 'Maid must be at least 18 years old.';
        header("Location: maid-request.php");
        exit();
    }

    // Insert into the database (example query, adjust according to your table structure)
    $sql = "INSERT INTO tbl_maid (cus_id, aprt_id, req_date, m_name, father_name, mother_name, blood_type, gender, m_phone, dob, m_nic, m_address, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $cus_id, $aprt_id, $currentDateTime, $name, $father, $mother, $blood_type, $gender, $phone, $dob, $nic, $address, $status);

    if ($stmt->execute()) {
        // Generate the maid ID
        $last_id = $conn->query("SELECT MAX(m_id) AS last_id FROM tbl_maid")->fetch_assoc()['last_id'];
        $last_id = $last_id ? (int)substr($last_id, 4) : 0;
        $m_id = 'MAID' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        // Update the maid_id in the table
        $sql1 = "UPDATE tbl_maid SET m_id = '$m_id' ORDER BY id DESC LIMIT 1";
        $res1 = mysqli_query($conn, $sql1);

        if ($res1) {
            $_SESSION['form_success'] = 'Request successful!';
        } else {
            $_SESSION['form_error'] = 'Error updating the database.';
        }
    } else {
        $_SESSION['form_error'] = 'Error submitting the form.';
    }

    $stmt->close();
    $conn->close();

    // Redirect back with success or error message
    header("Location: maid-request.php");
    exit();
}
?>
