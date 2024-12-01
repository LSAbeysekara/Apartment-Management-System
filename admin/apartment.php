
<?php require('../config.php'); ?>
<?php if (!isset($_SESSION['staffname'])) {
  echo "<script> window.location.replace('../index.php'); </script>";
} else { ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script> -->
</head>
<body>
 <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2>Apartment Details</h2>
            <table id="apartmentTable" class="display">
            <thead>
                <tr>
                    <th>Apartment ID</th>
                    <th>Block No</th>
                    <th>Floor No</th>
                    <th>Unit No</th>
                    <th>Room Count</th>
                    <th>Bathroom Count</th>
                    <th>Area (sqft)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT a.id, a.aprt_id, a.cus_id, a.block_no, a.floor_no, a.aprt_no, a.room_count, a.bathroom_count, a.area, a.status, c.cus_name 
                          FROM tbl_apartment a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.id";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status = ($row['status'] == 'occupied' && $row['cus_id'] != null) ? "Occupied by " . $row['cus_name'] : $row['status'];
                        echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['block_no']}</td>
                                <td>{$row['floor_no']}</td>
                                <td>{$row['aprt_no']}</td>
                                <td>{$row['room_count']}</td>
                                <td>{$row['bathroom_count']}</td>
                                <td>{$row['area']}</td>
                                <td>{$status}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view' data-id='{$row['id']}'>View</button>
                                    <button class='btn-approve' data-id='{$row['id']}'>Edit</button> 
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#apartmentTable').DataTable();

            // View Button
            $('.btn-view').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_apartment_details.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        let data = JSON.parse(response);
                        Swal.fire({
                            title: 'Apartment Details',
                            html: `<strong>Apartment ID:</strong> ${data.aprt_id}<br>
                                   <strong>Block No:</strong> ${data.block_no}<br>
                                   <strong>Floor No:</strong> ${data.floor_no}<br>
                                   <strong>Unit No:</strong> ${data.aprt_no}<br>
                                   <strong>Room Count:</strong> ${data.room_count}<br>
                                   <strong>Bathroom Count:</strong> ${data.bathroom_count}<br>
                                   <strong>Area (sqft):</strong> ${data.area}<br>
                                   <strong>Status:</strong> ${data.status}<br>
                                   ${data.cus_name ? `<strong>Occupied by:</strong> ${data.cus_name}` : ''}`,
                            icon: 'info'
                        });
                    }
                });
            });

            // Edit Button
            $('.btn-approve').on('click', function() {
                var id = $(this).data('id');
                window.location.href = 'edit_apartment.php?id=' + id;
            });
        });
    </script>
    <script src="../script.js"></script>
</body>
</html>
<?php } ?>