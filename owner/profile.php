<?php include('../config.php'); ?>

<?php 
  if(isset($_SESSION['cus_id'])){
    $cus_id = $_SESSION['cus_id'];
  }else{
    header('Location:../index.php');
    exit();
  }
?>

<?php
  $sql2 = "SELECT * FROM tbl_customer WHERE cus_id='$cus_id'";
  $res2 = mysqli_query($conn, $sql2);
  $count2 = mysqli_num_rows($res2);

  if($count2>0){
      while($row=mysqli_fetch_assoc($res2)){
        $cus_name = $row['cus_name'];
        $cus_username = $row['cus_username'];
        $cus_email = $row['cus_email'];
        $cus_phone = $row['cus_phone'];
        $apartment_id = $row['apartment_id'];
        $status = $row['status'];

        $sql3 = "SELECT * FROM tbl_apartment WHERE cus_id='$cus_id'";
        $res3 = mysqli_query($conn, $sql3);
        $count3 = mysqli_num_rows($res3);

        if($count3>0){
            while($row=mysqli_fetch_assoc($res3)){
              $aprt_id = $row['aprt_id'];
              $block_no = $row['block_no'];
              $floor_no = $row['floor_no'];
              $aprt_no = $row['aprt_no'];
            }
          }
      }
    }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lahiru Sampath</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"
    />
    <link rel="stylesheet" href="styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
  <?php include 'header_nav.php'; ?>
    
    <div class="separate">
        <div class="left">
          <a href="#" class="navigation-link" style="display: contents;">
              <div class="navigation one">
                  <h4>Personal Information</h4>
              </div>
          </a><br>
          <a href="entry-pass-history.php" class="navigation-link">
              <div class="navigation two">
                  <h4>Entry Pass History</h4>
              </div>
          </a>
          <a href="fill-out-history.php" class="navigation-link">
              <div class="navigation three">
                  <h4>Fit Out Form History</h4>
              </div>
          </a>
          <a href="maid-request-history.php" class="navigation-link">
              <div class="navigation four">
                  <h4>Maid Pass History</h4>
              </div>
          </a>
          <a href="vehicle-pass-history.php" class="navigation-link">
              <div class="navigation five">
                  <h4>Vehicle Pass History</h4>
              </div>
          </a>
          <a href="javascript:void(0);" class="navigation-link" onclick="confirmLogout(event)">
              <div class="navigation six">
                  <h4>Log out</h4>
              </div>
          </a>
        </div>

        <div class="right" style="padding: 2vw 0 0 5vw">
            <div class="container">
              <div class="personal-info" style="margin-top:0px;">
                  <div class="topic" style="border:none;">
                      <h2>Personal Information</h2>
                      <a href="javascript:void(0);" onclick="enableEditing()" class="icon-link">
                          <span class="material-symbols-outlined">edit</span>
                      </a>
                  </div>
                  <form action="edit-profile.php" method="post" enctype="multipart/form-data">
                      <div class="image-container" style="margin-bottom: 5px;">
                          <?php
                          $targetDir = "../assets/images/profile_pic/";
                          $username = $cus_username; // Replace with the actual $username variable in your context
                          $defaultImage = "profile.jpg";
                          $profileImagePath = $targetDir . $defaultImage;

                          // Search for an existing profile image for the user
                          $userImage = glob($targetDir . $username . ".*"); // Finds any file named $username with any extension

                          // If an image with the username exists, set it as the profile image path
                          if (!empty($userImage)) {
                              $profileImagePath = $userImage[0]; // Use the first match found
                          }
                          ?>

                          <img src="<?php echo $profileImagePath; ?>" alt="Profile Picture" class="profile-image" id="profileImage"><br>
                          <input type="file" name="image" id="upload" class="upload-input" accept="image/*" onchange="previewImage(event)" disabled>
                          <label for="upload" class="upload-label">Choose Image</label>
                      </div>
                      <div class="floating-label" style="margin-bottom:5px;">
                          <input type="text" name="name" id="name" value="<?php echo $cus_name; ?>" readonly required />
                          <div class="labelline">Full Name</div>
                      </div>
                      <div class="floating-label" style="margin-bottom:5px;">
                          <input type="text" name="username" id="username" value="<?php echo $cus_username; ?>" readonly required />
                          <div class="labelline">Username</div>
                      </div>
                      <div class="floating-label" style="margin-bottom:5px;">
                          <input type="email" name="email" id="email" value="<?php echo $cus_email; ?>" readonly required />
                          <div class="labelline">Email</div>
                      </div>
                      <div class="floating-label" style="margin-bottom:5px;">
                          <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="0<?php echo $cus_phone; ?>" readonly required />
                          <div class="labelline">Phone number</div>
                      </div>
                      <div class="input-group" style="margin-bottom:3px;">
                          <div class="floating-label">
                              <input type="text" name="block" id="block" value="<?php echo $block_no; ?>" readonly required style="width: 24.8vw;"/>
                              <div class="labelline">Block Number</div>
                          </div>
                          <div class="floating-label">
                              <input type="text" name="floor" id="floor" value="<?php echo $floor_no; ?>" readonly required style="width: 24.8vw;"/>
                              <div class="labelline">Floor Number</div>
                          </div>
                          <div class="floating-label">
                              <input type="text" name="apartment" id="apartment" value="<?php echo $aprt_no; ?>" readonly required style="width: 24.8vw;"/>
                              <div class="labelline">Apartment Number</div>
                          </div>
                      </div>

                      <div id="editButtons" style="display: flex; justify-content: space-between; display: none; margin-bottom: 3px;">
                          <div>
                              <button class="pass-btn" type="button" onclick="changePassword()">Change Password</button>
                          </div>
                          <div>
                              <button type="submit" class="pass-btn">Submit</button>
                              <button class="cancel-btn" type="button" onclick="cancelEditing()">Cancel</button>
                          </div>
                      </div>

                  </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./owner-script.js"></script>

  </body>
</html>


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
<?php unset($_SESSION['form_error']); } ?>