
<?php require('../config.php'); ?>
<?php if (!isset($_SESSION['staffname'])) {
  echo "<script> window.location.replace('../index.php'); </script>";
} else { ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header>
            <h1>Maid Pass</h1>
        </header>

        
        <section class="tables-section">
            <div class="table-wrapper">
                <h2>Pending Maid Pass Requests</h2>
                <table class="styled-table" id="pmaid">
                    <thead>
                        <tr>
                            <th>Apartment ID</th>
                            <th>Maid ID</th>
                            <th>Maid Name</th>
                            <th>Gender</th>
                            <th>Nic</th>
                            <th>Customer Name</th>
                            <th>Req. Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                $query = "SELECT *, c.cus_name 
                          FROM tbl_maid a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id where a.status ='Pending' order by a.req_date desc";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        
                        echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['m_id']}</td>
                                <td>{$row['m_name']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['m_nic']}</td>
                                <td>{$row['cus_name']}</td>
                                <td>{$row['req_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view pbview'  data-id='{$row['m_id']}'>View</button>
                                    <button class='btn-approve' data-id='{$row['m_id']}'>Approved</button>
                                    <button class='btn-reject' data-id='{$row['m_id']}'>Reject</button> 
                                    </div>
                                </td>
                              </tr>";
                    }
                }
                ?>
                    </tbody>
                </table>
            </div>

            <div class="table-wrapper">
                <h2>Maid Pass History</h2>
                <table class="styled-table" id="maid">
                    <thead>
                        <tr>
                            <th>Apartment ID</th>
                            <th>Maid ID</th>
                            <th>Maid Name</th>
                            <th>Gender</th>
                            <th>Nic</th>
                            <th>Customer Name</th>
                            <th>Req. Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                $query = "SELECT *, c.cus_name 
                          FROM tbl_maid a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id where a.status !='Pending' order by a.req_date desc";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        
                        echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['m_id']}</td>
                                <td>{$row['m_name']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['m_nic']}</td>
                                <td>{$row['cus_name']}</td>
                                <td>{$row['req_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view bview'  data-id='{$row['m_id']}'>View</button>

                                    </div>
                                </td>
                              </tr>";
                    }
                }
                ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pmaid').DataTable();

            // View Button
            $('.pbview').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_maid_details.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        let data = JSON.parse(response);
                        Swal.fire({
                            title: 'Maid Details',
                            html: `<strong>Apartment ID:</strong> ${data.aprt_id}<br>
                                   <strong>Maid ID:</strong> ${data.m_id}<br>
                                   <strong>Maid Name:</strong> ${data.m_name}<br>
                                   <strong>Maid NIC:</strong> ${data.m_nic}<br>
                                   <strong>Maid Phone:</strong> ${data.m_phone}<br>
                                   <strong>Maid Address:</strong> ${data.m_address}<br>
                                   <strong>Gender:</strong> ${data.gender}<br>
                                   <strong>Blood Type:</strong> ${data.blood_type}<br>
                                   <strong>DOB:</strong> ${data.dob}<br>
                                   <strong>Father Name:</strong> ${data.father_name}<br>
                                   <strong>Mother Name:</strong> ${data.mother_name}<br>
                                   <strong>Request Date:</strong> ${data.req_date}<br>
                                   <strong>Status:</strong> ${data.status}<br>
                                   ${data.cus_name ? `<strong>Requested by:</strong> ${data.cus_name}` : ''}`,
                            icon: 'info'
                        });
                    }
                });

                
            });
            $('#maid').DataTable();

            // View Button
            $('.bview').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_maid_details.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        let data = JSON.parse(response);
                        Swal.fire({
                            title: 'Maid Details',
                            html: `<strong>Apartment ID:</strong> ${data.aprt_id}<br>
                                   <strong>Maid ID:</strong> ${data.m_id}<br>
                                   <strong>Maid Name:</strong> ${data.m_name}<br>
                                   <strong>Maid NIC:</strong> ${data.m_nic}<br>
                                   <strong>Maid Phone:</strong> ${data.m_phone}<br>
                                   <strong>Maid Address:</strong> ${data.m_address}<br>
                                   <strong>Gender:</strong> ${data.gender}<br>
                                   <strong>Blood Type:</strong> ${data.blood_type}<br>
                                   <strong>DOB:</strong> ${data.dob}<br>
                                   <strong>Father Name:</strong> ${data.father_name}<br>
                                   <strong>Mother Name:</strong> ${data.mother_name}<br>
                                   <strong>Request Date:</strong> ${data.req_date}<br>
                                   <strong>Start Date:</strong> ${data.st_date}<br>
                                   <strong>End Date:</strong> ${data.end_date}<br>                                                                      
                                   <strong>Status:</strong> ${data.status}<br>
                                   ${data.cus_name ? `<strong>Requested by:</strong> ${data.cus_name}` : ''}`,
                            icon: 'info'
                        });
                    }
                });

                
            });
            // Edit Button
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                window.location.href = 'edit_apartment.php?id=' + id;
            });
        });
    </script>
    <script src="../script.js"></script>
</body>
</html>
<?php } ?>