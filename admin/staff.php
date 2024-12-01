<?php
require('../config.php');
$page_title = "Staff";
if (!isset($_SESSION['staffname'])) {
    echo "<script> window.location.replace('../index.php'); </script>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2>Staff Details</h2>
            <table id="staffTable" class="styled-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>NIC</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM tbl_employee";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['emp_id']}</td>
                                <td>{$row['emp_name']}</td>
                                <td>{$row['emp_phone']}</td>
                                <td>{$row['emp_email']}</td>
                                <td>{$row['emp_nic']}</td>
                                <td>{$row['emp_type']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <div class='button-container'>
                                        <button class='btn-view' data-id='{$row['id']}'>View</button>
                                        <button class='btn-edit' data-id='{$row['id']}'>Edit</button>";
                            if ($row['status'] === 'Active') {
                                echo "<button class='btn-deactivate' data-id='{$row['id']}'>Deactivate</button>";
                            } else {
                                echo "<button class='btn-activate' data-id='{$row['id']}'>Activate</button>";
                            }
                            echo "</div>
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
        $('#staffTable').DataTable();

        // View Button
        $('.btn-view').on('click', function() {
            var id = $(this).data('id');
            $.ajax({
                url: 'get_staff_details.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    let data = JSON.parse(response);
                    Swal.fire({
                        title: 'Staff Details',
                        html: `<strong>Employee ID:</strong> ${data.emp_id}<br>
                               <strong>Name:</strong> ${data.emp_name}<br>
                               <strong>Phone:</strong> ${data.emp_phone}<br>
                               <strong>Email:</strong> ${data.emp_email}<br>
                               <strong>NIC:</strong> ${data.emp_nic}<br>
                               <strong>Type:</strong> ${data.emp_type}<br>
                               <strong>Status:</strong> ${data.status}<br>
                               <strong>Entry Date:</strong> ${data.ent_date}<br>
                               <strong>Entered By:</strong> ${data.ent_by}`,
                        icon: 'info'
                    });
                }
            });
        });

        // Edit Button
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            window.location.href = 'edit_staff.php?id=' + id;
        });

        // Deactivate/Activate Button
        function updateStaffStatus(id, action) {
            $.ajax({
                type: 'POST',
                url: 'deactivate_staff.php',
                data: { id: id, action: action },
                success: function(response) {
                    let res = JSON.parse(response);
                    Swal.fire({
                        title: res.success ? 'Success!' : 'Error!',
                        text: res.message,
                        icon: res.success ? 'success' : 'error'
                    }).then(() => {
                        if (res.success) location.reload();
                    });
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while updating staff status.', 'error');
                }
            });
        }

        // Deactivate button click
        $('.btn-deactivate').on('click', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to deactivate this staff member?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, deactivate'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStaffStatus(id, 'deactivate');
                }
            });
        });

        // Activate button click
        $('.btn-activate').on('click', function() {
            let id = $(this).data('id');
            updateStaffStatus(id, 'activate');
        });
    });
    </script>
</body>
</html>
<?php } ?>
