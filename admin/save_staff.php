<?php

require('../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generatePassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

// Get form data
$name = $_POST['name'];
$username = $_POST['username'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$type = $_POST['type'];
$staffname = $_SESSION['staffname'];

// Check if employee with the NIC, email, or username already exists
$checkQuery = "SELECT * FROM tbl_employee WHERE emp_nic = '$nic' OR emp_email = '$email' OR emp_username = '$username'";
$checkResult = $conn->query($checkQuery);

if ($checkResult->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Employee with this NIC, Email, or Username already exists']);
    exit;
}

// Generate new employee ID
$last_id = $conn->query("SELECT MAX(id) AS last_id FROM tbl_employee")->fetch_assoc()['last_id'];
$new_emp_id = 'EMP' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

// Generate password and hash it
$password = generatePassword();
$hash_psw = sha1($password);

$insertQuery = "INSERT INTO tbl_employee (emp_id, emp_name, emp_phone, emp_email, emp_nic, 
                emp_username, emp_password, emp_type, ent_date, ent_by, status)
                VALUES ('$new_emp_id', '$name', '$phone', '$email', '$nic', 
                '$username', '$hash_psw', '$type', NOW(), '$staffname', 'active')";

if ($conn->query($insertQuery)) {
    // Send email with login credentials
    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ontracklk@gmail.com';
    $mail->Password = 'wwfe nkvd hzor jqej';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('admin@ams.lk', 'AMS');
    $mail->addReplyTo('admin@ams.lk', 'AMS Support');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Welcome to ICONIC GALAXY - Staff Account Created';
    $mail->Body = "
    <p>Dear $name,</p>
    <p>Your staff account has been created. Below are your login details:</p>
    <p><strong>Login URL:</strong> <a href='http://localhost/login'>http://localhost/ams</a></p>
    <p><strong>Username:</strong> $username</p>
    <p><strong>Password:</strong> $password</p>
    <br>
    <p>Please change your password after first login.</p>
    <p>Thank you!</p>";

    if (!$mail->send()) {
        echo json_encode(['success' => false, 'message' => 'Staff registered but failed to send email: ' . $mail->ErrorInfo]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Staff registered successfully! Email sent with login details.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error registering staff.']);
}
?>
