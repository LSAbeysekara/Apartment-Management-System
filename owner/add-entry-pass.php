<?php
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $nic = $_POST['nic'];
    $person_count = $_POST['person_count'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $passType = $_POST['pass_type'];
    $aprt_id = $_POST['aprt_id'];
    $status = 'Pending';

    if(isset($_SESSION['cus_id'])){
        $cus_id = $_SESSION['cus_id'];
    }else{
        header('Location:../index.php');
        exit();
    }


    // Get current Date and time
    date_default_timezone_set('Asia/Colombo');

    // Get current date and time in Sri Lanka timezone
    $currentDateTime = date("Y-m-d H:i:s");

    // Save form data to the database
    $sql = "INSERT INTO tbl_entrypass (cus_id, aprt_id, ent_name, ent_phone, ent_nic, person_count, pass_type, req_date, st_date, end_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $cus_id, $aprt_id, $name, $phone, $nic, $person_count, $passType, $currentDateTime, $startDate, $endDate, $status);

    if ($stmt->execute()) {
        $last_id = $conn->query("SELECT MAX(ent_id) AS last_id FROM tbl_entrypass")->fetch_assoc()['last_id'];

        // Convert last_id to an integer, handle the case when last_id is null (i.e., no records in the table)
        $last_id = $last_id ? (int)substr($last_id, 4) : 0; // Extract numeric part and convert to int

        // Generate the new ent_id
        $ent_id = 'ENT' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        // Update the ent_id in the complaint table
        $sql1 = "UPDATE tbl_entrypass SET ent_id = '$ent_id' ORDER BY id DESC LIMIT 1";
        $res1 = mysqli_query($conn, $sql1);

        if($res1 == true){
            // Handle the uploaded image
            $targetDir = "../assets/images/entry_pass/";
            $profileImage = $_FILES['image']['name'];

            if (!empty($profileImage)) {
                $imageFileType = strtolower(pathinfo($profileImage, PATHINFO_EXTENSION));
                $uniqueImageName = $ent_id . "." . $imageFileType; // Use $ent_id as the file name
                $targetFilePath = $targetDir . $uniqueImageName;

                // Validate image type and upload
                $allowedTypes = ['jpg', 'jpeg', 'png'];
                if (in_array($imageFileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {

                        $_SESSION['add-entry-pass-ok'] = 'Entry pass created successfully.';
                        header("Location: entry-pass.php");

                    } else {
                        $_SESSION['upload_error'] = 'Image upload failed.';
                    }
                } else {
                    $_SESSION['type_error'] = 'Only JPG, JPEG, and PNG files are allowed.';
                }
            } else {
                $_SESSION['add-entry-pass-error'] = 'Error';
                header("Location: entry-pass.php");
            }
        }
        
    } else {
        $_SESSION['add-entry-pass-error'] = 'Error';
        header("Location: entry-pass.php");
    }

    $stmt->close();
    $conn->close();
}
?>
