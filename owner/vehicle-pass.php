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
    <title>New Vehicle Pass</title>
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
        <h4 style="font-size:14px"><a href="request-form.php">Request Form</a>> New Vehicle Pass</h4>
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
            <a href="maid-request.php" class="navigation-link">
                <div class="navigation three">
                    <h4>Application for New Maid Pass</h4>
                </div>
            </a>
            <a href="#" class="navigation-link" style="display: contents;">
                <div class="navigation four">
                    <h4>Application for New Vehicle Pass</h4>
                </div>
            </a>
        </div>
        <div class="right">
        <section class="form-section2">
            <h1>Application for New Vehicle Pass</h1>
            <form action="add-vehicle-pass.php" method="post">
              <div class="floating-label">
                  <input type="text" name="number" id="number" required />
                  <div class="labelline">Vehicle Number</div>
              </div>
              <div class="floating-label">
                  <input type="text" name="color" id="color" required />
                  <div class="labelline">Vehicle Color</div>
              </div>
              <div class="floating-label">
                  <select name="vehicle-type" id="vehicle-type">
                    <option value="" disabled selected>Select your vehicle type</option>
                    <option value="Car">Car</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Bicycle">Bicycle</option>
                    <option value="Truck">Truck</option>
                    <option value="Bus">Bus</option>
                    <option value="Van">Van</option>
                    <option value="Tractor">Tractor</option>
                  </select>
                  <div class="labelline">Vehicle Type</div>
              </div>

              <input type="hidden" name="aprt_id" value="<?php echo $apartment_id; ?>">
              
              <button type="submit" name="submit" style="width:100%;">Submit</button>    
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