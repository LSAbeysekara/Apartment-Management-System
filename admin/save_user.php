<?php
require('../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Helper function to generate random password
function generatePassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

// Get form data
$name = $_POST['name'];
$gender = $_POST['gender'];
$nic = $_POST['nic'];
$email = $_POST['email'];
$tel = $_POST['tel'];
$app_no = $_POST['app_no'];
$staffname=$_SESSION['staffname'];

$checkQuery = "SELECT * FROM tbl_customer WHERE cus_nic = '$nic' OR cus_email = '$email'";
$checkResult = $conn->query($checkQuery);

if ($checkResult->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Customer with this NIC or Email already exists']);
    exit;
}

$last_id = $conn->query("SELECT MAX(id) AS last_id FROM tbl_customer")->fetch_assoc()['last_id'];
$new_cus_id = 'CUS' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

$last_en_id = $conn->query("SELECT MAX(id) AS last_ids FROM tbl_aptenroll")->fetch_assoc()['last_ids'];
$new_en_id = 'ENR' . str_pad($last_en_id + 1, 4, '0', STR_PAD_LEFT);

$password = generatePassword();
$hash_psw =sha1($password);

$insertQuery = "INSERT INTO tbl_customer (cus_id, cus_name, cus_gender, cus_nic, cus_email, cus_phone, apartment_id, cus_password,ent_by,cus_username)
VALUES ('$new_cus_id', '$name', '$gender', '$nic', '$email', '$tel', '$app_no', '$hash_psw','$staffname','$new_cus_id')";


$insertQuery2 ="INSERT INTO tbl_aptenroll (en_id, cus_id, apt_id)VALUES ('$new_en_id', '$new_cus_id', '$app_no')";

$updateq = "UPDATE `tbl_apartment` SET `status`='Occupied',`cus_id`='$new_cus_id' WHERE `aprt_id`='$app_no'";
if ($conn->query($insertQuery)) {
    $conn->query($updateq);
    $conn->query($insertQuery2);
    // Send email to customer with login details
    $mail = new PHPMailer;
    $mail->isSMTP();                                    // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                   // Specify SMTP server (Use a valid SMTP host)
    $mail->SMTPAuth = true;                             // Enable SMTP authentication
    $mail->Username = 'ontracklk@gmail.com';         // SMTP username
    $mail->Password = 'wwfe nkvd hzor jqej';            // SMTP password
    $mail->SMTPSecure = 'tls';                          // Enable TLS encryption
    $mail->Port = 587;                                  // TCP port to connect to

    $mail->setFrom('admin@ams.lk', 'AMS');             // Sender email address and name
    $mail->addReplyTo('admin@ams.lk', 'AMS Support');  // Reply-To email address       // Sender email address
    $mail->addAddress($email, $name);                   // Recipient email address

    // Email subject and body
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = 'Welcome to ICONIC GALAXY!';
    $mail->Body    = "
    <p>Dear $name,</p>
    <p>Your account has been created. Below are your login details:</p>
    <p><strong>Login URL:</strong> <a href='http://localhost/login'>http://localhost/ams</a></p>
    <p><strong>Username:</strong> $new_cus_id</p>
    <p><strong>Password:</strong> $password</p>
    <br>
    <p>Thank you!</p>";

    if (!$mail->send()) {
       
        echo json_encode(['success' => false, 'message' => 'Customer registered but failed to send email: ' . $mail->ErrorInfo]);
    } else {
        
        echo json_encode(['success' => true, 'message' => 'Customer registered successfully! Email sent with login details.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error registering customer.']);
}
?>
