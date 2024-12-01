
<?php require('../config.php'); 
$page_title="Users"; ?>
<?php if (!isset($_SESSION['staffname'])) {
  echo "<script> window.location.replace('../index.php'); </script>";
} else { ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2>Customer Details</h2>
            <table id="cusTable" class="styled-table">
            <thead>
                <tr>
                    
                    <th>CUS ID</th>
                    <th>CUS Name</th>
                    <th>NIC</th>
                    <th>Phone</th>
                    <th>Apartment No</th>
                    <th>User Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM `tbl_customer`";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status = ($row['status']);
                        echo "<tr>
                        <td>{$row['cus_id']}</td>
                        <td>{$row['cus_name']}</td>
                        <td>{$row['cus_nic']}</td>
                        <td>{$row['cus_phone']}</td>
                        <td>{$row['apartment_id']}</td>
                        <td>{$row['cus_username']}</td>
                        <td>{$status}</td>
                        <td>
                        <div class='button-container'>
                            <button class='btn-view' data-id='{$row['id']}'>View</button>
                            <button class='btn-approve' data-id='{$row['id']}'>Edit</button>";
                
                if ($status !== 'end') {
                    echo "<button class='btn-reject' data-id='{$row['apartment_id']}'>Remove</button>";
                }else{
                    echo "<button class='btn-disable' data-id='{$row['apartment_id']}' disabled>Remove</button>";

                }
                
                echo "    </div>
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
    <script src="../script.js"></script>

</body>
</html>
<?php } ?>