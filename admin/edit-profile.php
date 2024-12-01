<?php
include '../config.php'; 

// Check if the user is logged in and `staffid` is set in the session
if (isset($_SESSION['staffid'])) {
        $staffid = $_SESSION['staffid'];
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $nic = $_POST['nic'];
            $phone = $_POST['phone'];
    
            // Initialize a variable to track the profile picture update
            $profileUpdated = false;
    
            // Check if a new profile picture is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../assets/images/profile_pic/";
                $fileName = basename($_FILES['image']['name']);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = $username . '.' . $fileType; // Use `username` instead of `staffid`
                $targetFilePath = $targetDir . $newFileName;
    
                // Allow only specific file formats
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileType), $allowedTypes)) {
                    // Delete any existing profile picture for the user
                    $oldImages = glob($targetDir . $username . ".*");
                    foreach ($oldImages as $oldImage) {
                        unlink($oldImage);
                    }
    
                    // Upload the new file
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $profileUpdated = true;
                    } else {
                        $_SESSION['form_error'] = "Failed to upload the profile picture.";
                        header("Location: profile.php");
                        exit;
                    }
                } else {
                    $_SESSION['form_error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                    header("Location: profile.php");
                    exit;
                }
            }
    
            // Prepare SQL update query
            $query = "UPDATE `tbl_employee` SET `emp_name` = ?, `emp_username` = ?, `emp_email` = ?, `emp_nic` = ?, `emp_phone` = ? WHERE `emp_id` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssss", $name, $username, $email, $nic, $phone, $staffid);
    
            // Execute the update query
            if ($stmt->execute()) {
                $_SESSION['form_success'] = "Profile updated successfully.";
            } else {
                $_SESSION['form_error'] = "Failed to update profile information.";
            }
    
            $stmt->close();
            header("Location: profile.php");
            exit;
        }
    } else {
    // Redirect to login page if `staffid` is not set
    header("Location: ../index.php");
    exit;
}
?>
