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
    <title>Change Password</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"
    />
    <link rel="stylesheet" href="styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
  <?php include 'header_nav.php'; ?>

    <div style="padding:1vw 0 10px 2vw;">
        <h4 style="font-size:14px"><a href="profile.php">Personal Information</a>> Change Password</h4>
    </div>
    
    <div class="separate">
        <div class="left">
            <a href="#" class="navigation-link" style="display: contents;">
                <div class="navigation one">
                    <h4>Change Password</h4>
                </div>
            </a>
        </div>

        <div class="right" style="padding: 2vw 0 0 5vw">
            <div class="container">
              <div class="personal-info" style="margin-top:0px;">
                  <div class="topic" style="border:none;">
                      <h2>Change Password</h2>
                  </div>
                  <form action="edit-password.php" method="post" enctype="multipart/form-data">
                      <div>Current Password</div>
                      <div class="floating-label" style="margin-bottom:5px; position: relative;">
                          <input type="password" name="cpassword" id="cpassword" required />
                          <div class="labelline">Current Password</div>
                          <span onclick="togglePassword('cpassword', 'toggleIcon1')" class="toggle-password">
                              <span id="toggleIcon1" class="material-symbols-outlined">visibility</span>
                          </span>
                      </div>

                      <div>New Password</div>
                      <div class="floating-label" style="margin-bottom:5px; position: relative;">
                          <input type="password" name="npassword" id="npassword" required />
                          <div class="labelline">New Password</div>
                          <span onclick="togglePassword('npassword', 'toggleIcon2')" class="toggle-password">
                              <span id="toggleIcon2" class="material-symbols-outlined">visibility</span>
                          </span>
                      </div>

                      <div>Confirm Password</div>
                      <div class="floating-label" style="margin-bottom:5px; position: relative;">
                          <input type="password" name="conpassword" id="conpassword" required />
                          <div class="labelline">Confirm Password</div>
                          <span onclick="togglePassword('conpassword', 'toggleIcon3')" class="toggle-password">
                              <span id="toggleIcon3" class="material-symbols-outlined">visibility</span>
                          </span>
                      </div>

                      <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">

                      <span id="countdown" style="color: red; display: none; margin-left: 10px;"></span>
                      <button type="submit" id="pass" class="pass-btn">Submit</button>
                  </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./owner-script.js"></script>

  </body>
</html>


