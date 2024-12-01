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
    <title>Maid Request History</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"
    />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
  <?php include 'header_nav.php'; ?>
    
    <div class="separate">
        <div class="left">
            <a href="profile.php" class="navigation-link">
                <div class="navigation one">
                    <h4>Personal Information</h4>
                </div>
            </a>
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
            <a href="#" class="navigation-link" style="display: contents;">
                <div class="navigation four">
                    <h4>Maid Pass History</h4>
                </div>
            </a><br>
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
              <div class="personal-info" style="margin-top:0px; padding:2px;">
                <div class="table-container" style="max-width:100%;">
                    <div class="topic" style="padding-top:10px;">
                        <h2>Maid Pass History</h2>
                    </div>

                    <div class="table-history" style="max-width:98%;">
                      <table id="cusTable" class="styled-table" style="max-width:100%;">
                          <thead>
                              <tr>
                                  <th>Requested Date</th>
                                  <th>Name</th>
                                  <th>Father</th>
                                  <th>Mother</th>
                                  <th>DOB</th>
                                  <th>NIC</th>
                                  <th>Phone</th>
                                  <th>Address</th>
                                  <th>Start Date</th>
                                  <th>End Date</th>
                                  <th>Status</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                              $query = "SELECT * FROM `tbl_maid` WHERE cus_id = '$cus_id'";
                              $result = $conn->query($query);
                              if ($result->num_rows > 0) {
                                  while ($row = $result->fetch_assoc()) {
                                      $status = ($row['status']);
                                      echo "<tr>
                                      <td>{$row['req_date']}</td>
                                      <td>{$row['m_name']}</td>
                                      <td>{$row['father_name']}</td>
                                      <td>{$row['mother_name']}</td>
                                      <td>{$row['dob']}</td>
                                      <td>{$row['m_nic']}</td>
                                      <td>{$row['m_phone']}</td>
                                      <td>{$row['m_address']}</td>
                                      <td>{$row['st_date']}</td>
                                      <td>{$row['end_date']}</td>
                                      <td>{$status}</td>
                                      
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


