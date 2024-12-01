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
    <title>New Fit Out Form</title>
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
        <h4 style="font-size:14px"><a href="request-form.php">Request Form</a>> New Fit Out Form</h4>
    </div>
    
    <div class="separate">
        <div class="left">
            <a href="entry-pass.php" class="navigation-link">
                <div class="navigation one">
                    <h4>Application for New Entry Pass</h4>
                </div>
            </a>
            <a href="#" class="navigation-link" style="display: contents;">
                <div class="navigation two">
                    <h4>Application for Fit Out Form</h4>
                </div>
            </a><br>
            <a href="maid-request.php" class="navigation-link">
                <div class="navigation three">
                    <h4>Application for New Maid Pass</h4>
                </div>
            </a>
            <a href="vehicle-pass.php" class="navigation-link">
                <div class="navigation four">
                    <h4>Application for New Vehicle Pass</h4>
                </div>
            </a>
        </div>
        <div class="right">
        <section class="form-section2">
            <h1>Application for New Fit Out Form</h1>
            <form action="add-fill-out.php" method="post">
                <div class="floating-label">
                    <input type="text" name="name" id="name" required />
                    <div class="labelline">Contractor / Company Name</div>
                    </div>
                    <div class="floating-label">
                        <input type="tel" name="phone" name="phone" pattern="[0-9]{10}" required />
                        <div class="labelline">Phone number</div>
                    </div>
                    <div class="floating-label">
                        <input type="text" name="nic" id="nic" required />
                        <div class="labelline">NIC</div>
                    </div>
                    <div class="floating-label">
                        <input type="number" name="person_count" id="person_count" required min="1"/>
                        <div class="labelline">Person Count</div>
                    </div>
                    <div class="floating-label" style="margin-bottom: 30px;">
                        <input type="date" name="start-date" id="start-date" required />
                        <div class="labelline">Start Date</div>
                    </div>
                    <div class="floating-label">
                        <input type="date" name="end-date" id="end-date" required />
                        <div class="labelline">End Date</div>
                    </div>
                    <div class="floating-label">
                        <input type="text" name="message" id="message" required />
                        <div class="labelline">Message</div>
                    </div>

                    <input type="hidden" name="aprt_id" value="<?php echo $apartment_id; ?>">

                    <button type="submit" style="width:100%;">Submit</button>    
                </div>
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
