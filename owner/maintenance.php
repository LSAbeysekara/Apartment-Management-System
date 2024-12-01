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
    <title>Maintenance</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"
    />
    <link rel="stylesheet" href="styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
  <?php include 'header_nav.php'; ?>
        
    <main>
      <div class="container">
        <section class="form-section">
          <h1>New Maintenance Request</h1>
          <form action="add-maintenance.php" method="post" enctype="multipart/form-data">
            <div class="floating-label">
              <input type="text" id="name" name="name" value="<?php echo $cus_name; ?>" readonly />
              <label for="name" class="labelline">Name</label>
            </div>

            <div class="floating-label">
              <input type="tel" id="phone" name="phone" value="0<?php echo $cus_phone; ?>" pattern="[0-9]{10}" required />
              <label for="phone" class="labelline">Phone number</label>
            </div>

            <div class="apartment-details">
              <div class="floating-label">
                <input type="text" id="block" name="block" value="<?php echo $block_no; ?>" readonly  />
                <label for="block" class="labelline">Block</label>
              </div>
              <div class="floating-label">
                <input type="text" id="floor" name="floor" value="<?php echo $floor_no; ?>" readonly  />
                <label for="floor" class="labelline">Floor</label>
              </div>
              <div class="floating-label">
                <input type="text" id="apartment" name="apartment" value="<?php echo $aprt_no; ?>" readonly  />
                <label for="apartment" class="labelline">Apartment</label>
              </div>
            </div>

            <div class="floating-label">
              <textarea id="complaint" name="complaint" required></textarea>
              <label for="complaint" class="labelline">Maintenance Request</label>
            </div>

            <div class="imagesShow">
              <div class="floating-label">
                <input type="file" id="image" name="images[]" accept="image/*" multiple onchange="previewImages(event)" />
              </div>
            </div>

            <div class="showImg" id="imagePreview"></div>

            <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">

            <button type="submit">Submit</button>
          </form>
        </section>
      </div>
    </main>

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