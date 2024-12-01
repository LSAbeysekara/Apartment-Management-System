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
        <h4 style="font-size:14px;"><a href="bill.php">Bill and Payments</a>> New Request</h4>
    </div>
    
    <div class="separate">
        <div class="left">
            <a href="#" class="navigation-link" style="display: contents;">
                <div class="navigation one">
                    <h4>New Request</h4>
                </div>
            </a>
        </div>

        <?php 
        $sql3 = "SELECT * FROM tbl_customer WHERE cus_id='$cus_id'";
        $res3 = mysqli_query($conn, $sql3);
        $count3 = mysqli_num_rows($res3);

        if ($count3 > 0) {
            $row = mysqli_fetch_assoc($res3);
            $Electricity = $row['Electricity'];
            $Water = $row['Water'];
            $Telephone = $row['Telephone'];
            $Gas = $row['Gas'];
            $Gym = $row['Gym'];
            $Other = $row['Other'];
        }
        ?>

        <div class="right" style="padding: 2vw 0 0 5vw">
            <div class="container">
                <div class="personal-info" style="margin-top:0px;">
                    <div class="topic" style="border:none;">
                        <h2>New Request Form</h2>
                    </div>
                    <form action="add-request.php" method="post" enctype="multipart/form-data">
                      <div>Request Type</div>
                      <div class="floating-label" style="margin-bottom:5px; position: relative;">
                          <select name="request_type" id="request_type" required>
                              <option selected disabled>Select Request Type</option>
                              <option value="Electricity" <?php echo ($Electricity == 'request' || $Electricity == 'Yes') ? 'disabled' : ''; ?>>Electricity</option>
                              <option value="Water" <?php echo ($Water == 'request' || $Water == 'Yes') ? 'disabled' : ''; ?>>Water</option>
                              <option value="Telephone" <?php echo ($Telephone == 'request' || $Telephone == 'Yes') ? 'disabled' : ''; ?>>Telephone</option>
                              <option value="Gas" <?php echo ($Gas == 'request' || $Gas == 'Yes') ? 'disabled' : ''; ?>>Gas</option>
                              <option value="Gym" <?php echo ($Gym == 'request' || $Gym == 'Yes') ? 'disabled' : ''; ?>>Gym</option>
                              <option value="Other" <?php echo ($Other == 'request' || $Other == 'Yes') ? 'disabled' : ''; ?>>Other</option>
                          </select>
                      </div>

                      <button type="submit" class="pass-btn" id="submit-btn" disabled>Submit</button>
                  </form>
                </div>
            </div>
        </div>

    </div>

    <script src="./owner-script.js"></script>

  </body>
</html>


