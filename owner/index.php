<?php include('../config.php'); ?>

<?php 
  if(isset($_SESSION['cus_id'])) {
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
    <style>
        /* Overlay for dark background */
        #overlay {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Darker semi-transparent black */
            z-index: 999; /* Below the popup */
        }

        /* Popup styling */
        #notificationPopup {
            display: none; /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 2px solid #007BFF; /* Blue border */
            border-radius: 10px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3); /* Increased shadow for depth */
            width: 500px; /* Increased width of the popup */
            max-width: 90%; /* Responsive max width */
            padding: 30px; /* Increased padding inside the popup */
            z-index: 1000; /* Ensure it is on top of other elements */
            font-family: Arial, sans-serif; /* Font for the popup */
        }

        #notificationPopup h3 {
            margin-top: 0; /* Remove top margin */
            color: #007BFF; /* Heading color */
            font-size: 28px; /* Increased font size for heading */
        }

        #notificationList {
            list-style-type: none; /* Remove default list styling */
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margin */
        }

        #notificationList li {
            padding: 15px; /* Increased padding for each list item */
            border-bottom: 1px solid #ccc; /* Divider line */
            font-size: 18px; /* Increased font size for better readability */
        }

        #notificationList li:last-child {
            border-bottom: none; /* Remove border from the last item */
        }

        #closePopup {
            cursor: pointer; /* Change cursor to pointer */
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 12px; /* Font size for the close button */
            color: #fff; /* Color for the close button */
            background-color: #dc3545; /* Red background */
            padding: 4px 6px; /* Padding around the button */
            border-radius: 50%; /* Circular button */
            border: none; /* Remove border */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
        }

        #closePopup:hover {
            background-color: #c82333; /* Darker red on hover */
            transform: scale(1.1); /* Slightly enlarge on hover */
        }
    </style>
  </head>
  <body>
  <?php include 'header_nav.php'; ?>
        
    <div class="main-shortcut">
      <?php
        $bill_count = 0;
        /* This block of code is querying a database to retrieve the latest 10 records from the `tbl_bill` table where the `cus_id` column matches the value stored in the
        variable ``. Here's a breakdown of each step: */
        $sql2 = "SELECT * FROM tbl_bill WHERE cus_id='$cus_id'";
        $res2 = mysqli_query($conn, $sql2);
        $count2 = mysqli_num_rows($res2);

        if($count2>0){
            while($row=mysqli_fetch_assoc($res2)){
              $read_m = $row['read_m'];

              if($read_m == 'Not'){
                $bill_count++;
              }
            }
          }
      ?>

      <a href="read_message_all.php">
        <div class="card">
          <h3>Bill</h3>
          <?php 
              $query10 = "SELECT * FROM tbl_bill WHERE cus_id = '$cus_id' AND read_m = 'unread'";
              $result10 = $conn->query($query10);

              // Count the number of rows
              $bill_count = $result10 ? $result10->num_rows : 0;
          ?>
          <?php if($bill_count > 0){ ?>
            <span class="notification-badge"><?php echo $bill_count; ?></span>
          <?php } ?>
      </div>
      </a>
      <a href="entry-pass.php">
        <div class="card">
          <h3>Entry Pass</h3>
        </div>
      </a>
      <a href="complaint.php">
        <div class="card">
          <h3>Complaint</h3>
        </div>
      </a>
      <a href="maintenance.php">
        <div class="card">
          <h3>Maintenance</h3>
        </div>
      </a>
    </div>

    <div class="history">
      <div class="table-container">
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

                // Updated query to sort by bill_month in descending order
                $query = "SELECT * FROM tbl_bill WHERE cus_id = '$cus_id' ORDER BY STR_TO_DATE(bill_month, '%M %Y') DESC";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $bill_month = $row['bill_month'];
                        $read_m = $row['read_m'];
                        
                        $date = DateTime::createFromFormat('F Y', $bill_month);
                        $formattedDate = $date->format('Y - m');

                        if($read_m == "unread"){
                          echo "<tr style='background-color:#eebbb8;'>";
                        }else{
                          echo "<tr>";
                        }
                        echo "
                                <td>{$row['bill_id']}</td>
                                <td>{$row['bill_type']}</td>
                                <td>{$row['bill_month']}</td>
                                <td>Rs. {$row['amount']}</td>
                                <td>{$row['message']}</td>
                                <td>
                                    <div class='button-container'>
                                        <a href='read_message.php?bill_id={$row['bill_id']}'><button class='btn-view'>View</button></a>
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

    <script src="./owner-script.js"></script>

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

<!------------------------ Notification showing coding part ------------------------->
<?php if(isset($_SESSION['notification'])){ ?>
    <?php
      $sql = "SELECT message FROM tbl_notify 
              WHERE status = 'active' 
              AND CURDATE() BETWEEN start_date AND end_date";

      $res = mysqli_query($conn, $sql);
      $notifications = [];

      if ($res && mysqli_num_rows($res) > 0) {
          while ($row = mysqli_fetch_assoc($res)) {
              $notifications[] = $row['message'];
          }
      }
    ?>

    <div id="overlay"></div>
      <div id="notificationPopup">
        <div id="closePopup">X</div>
        <h3>Special Notices</h3>
        <ul id="notificationList">
            <?php
            if (!empty($notifications)) {
                foreach ($notifications as $message) {
                    echo "<li>" . htmlspecialchars($message) . "</li>";
                }
            } else {
                echo "<li>No active notifications at this time.</li>";
            }
            ?>
        </ul>
    </div>

    <script>
        // Show the popup when the page loads
        window.onload = function() {
            document.getElementById('notificationPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block'; // Show overlay

            // Close the popup when the close button is clicked
            document.getElementById('closePopup').onclick = function() {
                closePopup();
            };
        };

        function closePopup() {
            document.getElementById('notificationPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none'; // Hide overlay
        }
    </script>

<?php  unset($_SESSION['notification']); } ?>
    
  </body>
</html>

<?php if(isset($_SESSION['login-ok'])){ ?>
    <script>
        swal.fire({
            position: "top-end",
            icon: "success",
            title: "Welcome, <?php echo $cus_name; ?>",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
<?php   unset($_SESSION['login-ok']); } ?>