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
    <title>New Maid Pass</title>
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
        <h4 style="font-size:14px"><a href="request-form.php">Request Form</a>> New Maid Pass</h4>
    </div>
    
    <div class="separate">
        <div class="left">
            <a href="entry-pass.php" class="navigation-link">
                <div class="navigation one">
                    <h4>Application for New Entry Pass</h4>
                </div>
            </a>
            <a href="fill-out.php" class="navigation-link">
                <div class="navigation two">
                    <h4>Application for Fit Out Form</h4>
                </div>
            </a>
            <a href="#" class="navigation-link" style="display: contents;">
                <div class="navigation three">
                    <h4>Application for New Maid Pass</h4>
                </div>
            </a><br>
            <a href="vehicle-pass.php" class="navigation-link">
                <div class="navigation four">
                    <h4>Application for New Vehicle Pass</h4>
                </div>
            </a>
        </div>
        <div class="right">
        <section class="form-section2">
            <h1>Application for New Maid Pass</h1>
            <form action="add-maid.php" method="post">
                <div class="floating-label">
                    <input type="text" name="name" id="name" required />
                    <div class="labelline">Full Name</div>
                </div>
                <div class="floating-label">
                    <input type="text" name="father" id="father" required />
                    <div class="labelline">Father's Name</div>
                </div>
                <div class="floating-label">
                        <input type="text" name="mother" id="mother" required/>
                        <div class="labelline">Mother's Name</div>
                    </div>
                <div class="horizontal-container">
                    <div class="floating-label" style="width: 38.5vw;">
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required />
                        <div class="labelline">Phone number</div>
                    </div>
                    <div class="floating-label" style="width: 38.5vw; margin-bottom: 30px;">
                        <input type="date" name="dob" id="start-date" required />
                        <div class="labelline">Date of Birth</div>
                    </div>
                </div>
                <div class="horizontal-container">
                    <div class="floating-label" style="width: 25vw;">
                        <input type="text" name="nic" id="nic" required />
                        <div class="labelline">NIC</div>
                    </div>
                    <div class="floating-label" style="width: 25vw;">
                        <select name="gender" id="">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <div class="labelline">Gender</div>
                    </div>
                    <div class="floating-label" style="width: 25vw;">
                        <select name="blood_type" id="">
                            <option selected disabled>Select Blood Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                        <div class="labelline">Blood Type</div> 
                    </div>
                </div>
                
                <div class="floating-label">
                    <textarea name="address" id="address" required></textarea>
                    <div class="labelline">Address</div>
                </div>

                <input type="hidden" name="aprt_id" value="<?php echo $apartment_id; ?>">

                <button type="submit" style="width:100%;">Submit</button>    
            </form>
        </section>
    </div>
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
