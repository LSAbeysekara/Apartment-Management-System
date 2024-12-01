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
    <title>Home</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
  </head>
  <body>
    <?php include 'header_nav.php'; ?>
    <div class="bill-form">
        <div class="topic">
                <h2 style="text-align:center;">Total Outstanding</h2><br>
                <a href="new-request.php" class="icon-link" style="margin:5px 10px 0 0;">
                    <button class="pass-btn-new">New Request</button>
                </a>
            </div>
        <div class="main-shortcut-bill">
          <?php

            $sql5 = "SELECT * FROM tbl_customer WHERE cus_id='$cus_id'";
            $res5 = mysqli_query($conn, $sql5);
            $count5 = mysqli_num_rows($res5);

            if($count5>0){
              while($row=mysqli_fetch_assoc($res5)){
                $electricity1 = $row['Electricity'];
                $water1 = $row['Water'];
                $telephone1 = $row['Telephone'];
                $gas1 = $row['Gas'];
                $gym1 = $row['Gym'];
                $other1 = $row['Other'];


                $sql4 = "SELECT * FROM tbl_outstanding WHERE cus_id = '$cus_id'";
                $res4 = mysqli_query($conn, $sql4);
                $count4 = mysqli_num_rows($res4);

                if($count4>0){
                  while($row=mysqli_fetch_assoc($res4)){
                    $electricity = $row['Electricity'];
                    $water = $row['Water'];
                    $telephone = $row['Telephone'];
                    $gas = $row['Gas'];
                    $gym = $row['Gym'];
                    $other = $row['Other'];

                    if($electricity1 == 'Yes'){
                    ?>
                      <div class="card-bill">
                        <h5>Electricity</h5>
                        <?php if($electricity>0){ ?>
                          <h6 class="price red">Rs <?php echo $electricity; ?> </h6>
                        <?php }else{ ?>
                          <h6 class="price green">Rs <?php echo $electricity; ?> </h6>
                        <?php } ?>
                      </div>
                      <?php
                    }
                    if($water1 == 'Yes'){
                    ?>
                      <div class="card-bill">
                        <h5>Water</h5>
                        <?php if($water>0){ ?>
                          <h6 class="price red">Rs <?php echo $water; ?> </h6>
                        <?php }else{ ?>
                          <h6 class="price green">Rs <?php echo $water; ?> </h6>
                        <?php } ?>
                      </div>
                      <?php
                    }
                    if($telephone1 == 'Yes'){
                    ?>
                      <div class="card-bill">
                        <h5>Telephone</h5>
                        <?php if($telephone>0){ ?>
                          <h6 class="price red">Rs <?php echo $telephone; ?> </h6>
                        <?php }else{ ?>
                          <h6 class="price green">Rs <?php echo $telephone; ?> </h6>
                        <?php } ?>
                      </div>
                      <?php
                    }
                    if($gas1 == 'Yes'){
                    ?>
                      <div class="card-bill">
                        <h5>Gas</h5>
                        <?php if($gas>0){ ?>
                          <h6 class="price red">Rs <?php echo $gas; ?> </h6>
                        <?php }else{ ?>
                          <h6 class="price green">Rs <?php echo $gas; ?> </h6>
                        <?php } ?>
                      </div>
                      <?php
                    }
                    if($gym1 == 'Yes'){
                    ?>
                      <div class="card-bill">
                        <h5>Gym</h5>
                        <?php if($gym>0){ ?>
                          <h6 class="price red">Rs <?php echo $gym; ?> </h6>
                        <?php }else{ ?>
                          <h6 class="price green">Rs <?php echo $gym; ?> </h6>
                        <?php } ?>
                      </div>
                      <?php
                    }
                    if($other1 == 'Yes'){
                    ?>
                      <div class="card-bill">
                        <h5>Other</h5>
                        <?php if($other>0){ ?>
                          <h6 class="price red">Rs <?php echo $other; ?> </h6>
                        <?php }else{ ?>
                          <h6 class="price green">Rs <?php echo $other; ?> </h6>
                        <?php } ?>
                      </div>
                      <?php
                    }
                  }
                }
              }
            }
          ?>
        </div>
    </div>

    <div class="history">
      <div class="table-container">
          <div class="table-container" style="max-width:100%;">
              <div class="topic" style="padding-top:10px;">
                  <h2>Bill History</h2>
              </div>

              <div class="table-history" style="max-width:98%;">
                <table id="cusTable" class="styled-table" style="max-width:100%;">
                  <thead>
                      <tr>
                          <th>Bill ID</th>
                          <th>Bill Type</th>
                          <th>Month</th>
                          <th>Price</th>
                          <th>Message</th>
                          <th style="max-width:90px;">Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php
                      $this_month = date("Y - m");

                      $query = "SELECT * FROM tbl_bill WHERE cus_id = '$cus_id'";
                      $result = $conn->query($query);
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              $bill_month = $row['bill_month'];
                              $date = DateTime::createFromFormat('F Y', $bill_month);
                              $formattedDate = $date->format('Y - m');

                              echo "<tr>
                                      <td>{$row['bill_id']}</td>
                                      <td>{$row['bill_type']}</td>
                                      <td>{$row['bill_month']}</td>
                                      <td>Rs. {$row['amount']}</td>
                                      <td>{$row['message']}</td>
                                      <td>
                                          <div class='button-container'>
                                              <button class='btn-pay' 
                                                      data-bill-id='{$row['bill_id']}' 
                                                      data-bill-type='{$row['bill_type']}'
                                                      data-bill-month='{$row['bill_month']}'
                                                      data-amount='{$row['amount']}'
                                                      data-message='{$row['message']}'
                                                      popovertarget='mydiv'>
                                                  Pay Now
                                              </button>
                                          </div>
                                      </td>
                                  </tr>";
                          }
                      }
                      ?>
                  </tbody>
                </table>
              </div>
          </div>
      </div>
    </div>


    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popover -->
    <div class="pop">
        <div id="mydiv">
            <button class="close-btn">&times;</button>
            <div id="billDetails">
                <!-- Bill details will be dynamically loaded here -->
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cusTable').DataTable();

            // View Button
            $('.btn-view').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_cus_details.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        let data = JSON.parse(response);
                        Swal.fire({
                            title: 'Customer Details',
                            html: `<strong>CUS ID:</strong> ${data.cus_id}<br>
                                   <strong>Customer Name:</strong> ${data.cus_name}<br>
                                   <strong>NIC No:</strong> ${data.cus_nic}<br>
                                   <strong>Phone No:</strong> ${data.cus_phone}<br>
                                   <strong>Apartment ID:</strong> ${data.apartment_id}<br>
                                   <strong>Username:</strong> ${data.cus_username}<br>
                                   <strong>Status:</strong> ${data.status}<br>
                                   `,
                            icon: 'info'
                        });
                    }
                });
            });

            // Edit Button
            $('.btn-approve').on('click', function() {
                var id = $(this).data('id');
                window.location.href = 'edit_user.php?id=' + id;
            });

            $('.btn-reject').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // If confirmed, proceed with deletion
                                    suspendCus(id);
                                }
                            });
                    });
                });

        function suspendCus(id) {
                    $.ajax({
                        type: 'POST',
                        url: 'sus_cus.php',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            // Handle success response
                            Swal.fire(
                                'Deleted!',
                                'Course has been deleted.',
                                'success'
                            );
                            // Refresh DataTable after deletion
                            table.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting Course.',
                                'error'
                            );
                        }
                    });
                }
    </script>
    
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