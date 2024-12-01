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
    <title>Request Form</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
  <?php include 'header_nav.php'; ?>
    <div class="main-shortcut">
      <a href="entry-pass.php">
        <div class="card">
          <h3 style="color: black;">New Entry Pass</h3>
        </div>
      </a>

      <a href="fill-out.php">
        <div class="card">
          <h3 style="color: black;">New Fit out Form</h3>
        </div>
      </a>
      
      <a href="maid-request.php">
        <div class="card">
          <h3 style="color: black;">New Maid Request</h3>
        </div>
      </a>
      
      <a href="vehicle-pass.php">
        <div class="card">
          <h3 style="color: black;">New Vehicle Pass</h3>
        </div>
      </a>
      
    </div>

    <div class="multi-form">
      <div class="multi-card">    
        <div class="history">
          <div class="table-container">
            <div class="topic">
                <h2>Entry Pass</h2>
                <a href="entry-pass-history.php" class="icon-link">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>
            <table class="record-history-table">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Person Count</th>
                        <th>Pass Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      /* This block of code is querying a database to retrieve the latest 10 records from the `tbl_bill` table where the `cus_id` column matches the value stored in the
                      variable ``. Here's a breakdown of each step: */
                      $sql1 = "SELECT * FROM tbl_entrypass WHERE cus_id='$cus_id' ORDER BY id DESC LIMIT 3";
                      $res1 = mysqli_query($conn, $sql1);
                      $count1 = mysqli_num_rows($res1);

                      if($count1>0){
                          while($row=mysqli_fetch_assoc($res1)){
                              $ent_id = $row['ent_id'];
                              $ent_name = $row['ent_name']; 
                              $ent_phone = $row['ent_phone']; 
                              $ent_nic = $row['ent_nic'];
                              $person_count = $row['person_count'];
                              $pass_type = $row['pass_type'];
                              $req_date = $row['req_date'];
                              $st_date = $row['st_date'];
                              $date_st = date('Y-m-d', strtotime($st_date));
                              $end_date = $row['end_date'];
                              $date_end = date('Y-m-d', strtotime($end_date));
                              $status = $row['status'];
                              ?>

                              <tr class="liners"> 
                                  <td><?php echo $date_st; ?></td>
                                  <td><?php echo $date_end; ?></td>
                                  <td><?php echo $person_count; ?></td>
                                  <td><?php echo $pass_type; ?></td>

                                  <?php 
                                  if($status == "Pending"){ ?>
                                    <td class="status-pending" style="font-weight: bold;">Pending</td>
                                  <?php }else if($status == "Approved"){ ?>
                                    <td class="status-completed" style="font-weight: bold;">Approved</td>
                                  <?php }else{ ?>
                                     <td class="status-denied" style="font-weight: bold;">Denied</td>
                                  <?php } ?>
                              </tr>
                          
                          <?php    
                          }
                        }
                    ?>              
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="multi-card">
        <div class="history">
          <div class="table-container">
            <div class="topic">
                <h2>Fit out Form</h2>
                <a href="fill-out-history.php" class="icon-link">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>
            <table class="record-history-table">
                <thead>
                    <tr>
                        <th>Requested Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Person Count</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                      /* This block of code is querying a database to retrieve the latest 10 records from the `tbl_bill` table where the `cus_id` column matches the value stored in the
                      variable ``. Here's a breakdown of each step: */
                      $sql2 = "SELECT * FROM tbl_fillout WHERE cus_id='$cus_id' ORDER BY id DESC LIMIT 3";
                      $res2 = mysqli_query($conn, $sql2);
                      $count2 = mysqli_num_rows($res2);

                      if($count2>0){
                          while($row=mysqli_fetch_assoc($res2)){
                              $fo_id = $row['fo_id'];
                              $req_date1 = $row['rq_date']; 
                              $date_rq_1 = date('Y-m-d', strtotime($req_date1));
                              $st_date1 = $row['fillout_st_date'];
                              $date_st_1 = date('Y-m-d', strtotime($st_date1));
                              $end_date1 = $row['fillout_end_date'];
                              $date_end1 = date('Y-m-d', strtotime($end_date1));
                              $pass_count1 = $row['pass_count']; 
                              $status1 = $row['status'];
                              ?>

                              <tr class="liners"> 
                                  <td><?php echo $date_rq_1; ?></td>  
                                  <td><?php echo $date_st_1; ?></td>
                                  <td><?php echo $date_end1; ?></td>
                                  <td><?php echo $pass_count1; ?></td>
                                  <?php 
                                  if($status1 == "Pending"){ ?>
                                    <td class="status-pending" style="font-weight: bold;">Pending</td>
                                  <?php }else if($status1 == "Approved"){ ?>
                                    <td class="status-completed" style="font-weight: bold;">Approved</td>
                                  <?php }else{ ?>
                                     <td class="status-denied" style="font-weight: bold;">Denied</td>
                                  <?php } ?>
                              </tr>
                          
                          <?php    
                          }
                        }
                    ?>       
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="multi-card">    
        <div class="history">
          <div class="table-container">
            <div class="topic">
                <h2>Maid and Caretakers</h2>
                <a href="maid-request-history.php" class="icon-link">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>
            <table class="record-history-table">
                <thead>
                    <tr>
                        <th>Requested Date</th>
                        <th>Maid Name</th>
                        <th>Phone</th>
                        <th>Start Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      /* This block of code is querying a database to retrieve the latest 10 records from the `tbl_bill` table where the `cus_id` column matches the value stored in the
                      variable ``. Here's a breakdown of each step: */
                      $sql3 = "SELECT * FROM tbl_maid WHERE cus_id='$cus_id' ORDER BY id DESC LIMIT 3";
                      $res3 = mysqli_query($conn, $sql3);
                      $count3 = mysqli_num_rows($res3);

                      if($count3>0){
                          while($row=mysqli_fetch_assoc($res3)){
                              $m_id = $row['m_id'];
                              $req_date2 = $row['req_date']; 
                              $date_rq2 = date('Y-m-d', strtotime($req_date2));
                              $m_name = $row['m_name'];
                              $m_phone = $row['m_phone'];
                              $st_date2 = $row['st_date'];
                              $date_st2 = date('Y-m-d', strtotime($st_date2));
                              $status2 = $row['status'];
                              ?>

                              <tr class="liners"> 
                                  <td><?php echo $date_rq2; ?></td>  
                                  <td><?php echo $m_name; ?></td>
                                  <td><?php echo $m_phone; ?></td>
                                  <td><?php echo $date_st2; ?></td>
                                  <?php 
                                  if($status2 == "Pending"){ ?>
                                    <td class="status-pending" style="font-weight: bold;">Pending</td>
                                  <?php }else if($status2 == "Approved"){ ?>
                                    <td class="status-completed" style="font-weight: bold;">Approved</td>
                                  <?php }else{ ?>
                                     <td class="status-denied" style="font-weight: bold;">Denied</td>
                                  <?php } ?>
                              </tr>
                          
                          <?php    
                          }
                        }
                    ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="multi-card">    
        <div class="history">
          <div class="table-container">
            <div class="topic">
                <h2>Vehicle Pass</h2>
                <a href="vehicle-pass-history.php" class="icon-link">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>
            <table class="record-history-table">
                <thead>
                    <tr>
                        <th>Parking Spot</th>
                        <th>Vehicle Number</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Color</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      /* This block of code is querying a database to retrieve the latest 10 records from the `tbl_bill` table where the `cus_id` column matches the value stored in the
                      variable ``. Here's a breakdown of each step: */
                      $sql4 = "SELECT * FROM tbl_vehicle WHERE cus_id='$cus_id' ORDER BY id DESC LIMIT 3";
                      $res4 = mysqli_query($conn, $sql4);
                      $count4 = mysqli_num_rows($res4);

                      if($count4>0){
                          while($row=mysqli_fetch_assoc($res4)){
                              $v_id = $row['v_id'];
                              $v_number = $row['v_number'];
                              $v_type = $row['v_type'];
                              $v_color = $row['v_color'];
                              $p_spot = $row['p_spot'];
                              $status3 = $row['status'];
                              ?>

                              <tr class="liners"> 
                                  <td><?php echo $p_spot; ?></td>  
                                  <td><?php echo $v_number; ?></td>
                                  <td><?php echo $v_type; ?></td>
                                  <td><?php echo $v_color; ?></td>
                                  <?php 
                                  if($status3 == "Pending"){ ?>
                                    <td class="status-pending" style="font-weight: bold;">Pending</td>
                                  <?php }else if($status3 == "Approved"){ ?>
                                    <td class="status-completed" style="font-weight: bold;">Approved</td>
                                  <?php }else{ ?>
                                     <td class="status-denied" style="font-weight: bold;">Denied</td>
                                  <?php } ?>
                              </tr>
                          
                          <?php    
                          }
                        }
                    ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    
  </body>
</html>
