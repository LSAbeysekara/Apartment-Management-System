<?php include('../config.php'); ?>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Process the uploaded profile image if it exists
    $targetDir = "../assets/images/profile_pic/";
    $profileImage = $_FILES['image']['name'];

    // Update the user details in the database
    $sql = "UPDATE tbl_customer SET cus_name = ?, cus_email = ?, cus_phone = ? WHERE cus_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $username);

    if ($stmt->execute()) {
        // If an image is uploaded
        if (!empty($profileImage)) {
            // Remove any existing image file with the username
            foreach (glob($targetDir . $username . '.*') as $existingFile) {
                unlink($existingFile); // Delete the file
            }

            $imageFileType = strtolower(pathinfo($profileImage, PATHINFO_EXTENSION));
            $uniqueImageName = $username . "." . $imageFileType;  // Rename the image to the username
            $targetFilePath = $targetDir . $uniqueImageName;

            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $_SESSION['form_success'] = 'Data Saved Successfully';
                } else {
                    $_SESSION['form_error'] = 'Failed! Please try again';
                }
            } else {
                $_SESSION['form_error'] = 'Wrong File Type! Please try again';
            }
        }else{
            $_SESSION['form_success'] = 'Data Saved Successfully';
        }
    } else {
        $_SESSION['form_error'] = 'Failed! Please try again';
    }

    $stmt->close();
    $conn->close();

    header("Location: profile.php");
    exit; // Use exit to stop further script execution after redirecting
}
?>
