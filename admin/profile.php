<?php

include '../config.php'; 

// Check if the user is logged in and `staffid` is set in the session
if (isset($_SESSION['staffid'])) {
    $staffid = $_SESSION['staffid'];

    // Fetch employee data from `tbl_employee` table where `emp_id` matches `staffid`
    $query = "SELECT `emp_name`, `emp_phone`, `emp_email`, `emp_nic`, `emp_username` 
              FROM `tbl_employee` WHERE `emp_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $staffid);
    $stmt->execute();
    $stmt->bind_result($emp_name, $emp_phone, $emp_email, $emp_nic, $emp_username);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Staff ID not set in session.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
<?php include 'sidebar.php'; ?>

<?php if(isset($_SESSION['form_success'])) { ?>
    <script>
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "<?php echo $_SESSION['form_success']; ?>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
  <?php unset($_SESSION['form_success']); } ?>
  
  <?php if(isset($_SESSION['form_error'])) { ?>
    <script>
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "<?php echo $_SESSION['form_error']; ?>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
  <?php unset($_SESSION['form_error']);}?>

    <div class="main-content">
        <form action="edit-profile.php" method="post" enctype="multipart/form-data" class="form-container-prof">
            <div class="topic" style="border:none;">
                <h2>Personal Information</h2>
                <a href="javascript:void(0);" onclick="enableEditing()" class="icon-link">
                    <span class="material-symbols-outlined">edit</span>
                </a>
            </div>
            <div class="image-container-prof" style="margin-bottom: 5px;">
                <?php
                $targetDir = "../assets/images/profile_pic/";
                $defaultImage = "profile.jpg";
                $profileImagePath = $targetDir . $defaultImage;
                $userImage = glob($targetDir . $emp_username . ".*");

                if (!empty($userImage)) {
                     $profileImagePath = $userImage[0];
                 }
                ?>

                <img src="<?php echo $profileImagePath; ?>" alt="Profile Picture" class="profile-image-prof" id="profileImage"><br>
                <input type="file" name="image" id="upload" class="upload-input-prof" accept="image/*" onchange="previewImage(event)" disabled>
                <label for="upload" class="upload-label-prof">Choose Image</label>
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($emp_name); ?>" readonly required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($emp_username); ?>" readonly required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($emp_email); ?>" readonly required>
            </div>

            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" name="nic" id="nic" value="<?php echo htmlspecialchars($emp_nic); ?>" readonly required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($emp_phone); ?>" readonly required>
            </div>

            <div id="editButtons" class="button-container" style="display: none; margin-top: 10px;">
                <div class="submit-cancel-group">
                    <button type="submit" class="submit-btn">Submit</button>
                    <button class="cancel-btn" type="button" onclick="cancelEditing()">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    <script src="../script.js"></script>
</body>
</html>
