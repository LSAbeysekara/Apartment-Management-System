<?php include('../config.php'); ?>
<!DOCTYPE html>
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
        <div class="form-container">
            <h2>Fill Out History</h2>
            <section class="tables-section">
                <div class="table-wrapper">
                    <h2>Pending Fill Out Requests</h2>
                    <table class="styled-table" id="pmaid">
                        <thead>
                            <tr>
                                <th>Apartment ID</th>
                                <th>FO ID</th>
                                <th>Contractor Name</th>
                                <th>Contractor Phone</th>
                                <th>Pass Count</th>
                                <th>Message</th>
                                <th>Customer Name</th>
                                <th>Req. Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT *, c.cus_name 
                          FROM tbl_fillout a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id where a.status ='Pending' order by a.rq_date desc";
                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['fo_id']}</td>
                                <td>{$row['contr_name']}</td>
                                <td>{$row['contr_phone']}</td>
                                <td>{$row['pass_count']}</td>
                                <td>{$row['message']}</td>
                                <td>{$row['cus_name']}</td>
                                <td>{$row['rq_date']}</td>
                                <td>{$row['fillout_st_date']}</td>
                                <td>{$row['fillout_end_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view' id='pbview' data-id='{$row['fo_id']}'>View</button>
                                    <button class='btn-approve' data-id='{$row['fo_id']}'>Approved</button>
                                    <button class='btn-reject' data-id='{$row['fo_id']}'>Reject</button> 
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
                    <h2>Fill Out History</h2>
                    <table class="styled-table" id="maid">
                        <thead>
                            <tr>
                                <th>Apartment ID</th>
                                <th>FO ID</th>
                                <th>Contractor Name</th>
                                <th>Contractor Phone</th>
                                <th>Pass Count</th>
                                <th>Message</th>
                                <th>Customer Name</th>
                                <th>Req. Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT *, c.cus_name 
                          FROM tbl_fillout a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id where a.status !='Pending' order by a.rq_date desc";
                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['fo_id']}</td>
                                <td>{$row['contr_name']}</td>
                                <td>{$row['contr_phone']}</td>
                                <td>{$row['pass_count']}</td>
                                 <td>{$row['message']}</td>
                                <td>{$row['cus_name']}</td>
                                <td>{$row['rq_date']}</td>
                                <td>{$row['fillout_st_date']}</td>
                                <td>{$row['fillout_end_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view' id='bview' data-id='{$row['fo_id']}'>View</button>

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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pmaid').DataTable();
            $('#maid').DataTable();

            // View Button
            $('.btn-view').off('click');
            $('.btn-approve').off('click');
            $('.btn-reject').off('click');
            // View Button - Display Details in SweetAlert
            $(document).on('click', '.btn-view', function() {
                const id = $(this).data('id');
                console.log('Clicked View Button - ID:', id); // Debug log

                $.ajax({
                    url: 'get_fo_details.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response); // Debug log

                        if (response.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Fill Out Details',
                            html: `
                        <div class="details-container">
                            <p><strong>Apartment ID:</strong> ${response.aprt_id}</p>
                            <p><strong>FO ID:</strong> ${response.fo_id}</p>
                            <p><strong>Contractor Name:</strong> ${response.contr_name}</p>
                            <p><strong>Contractor Phone:</strong> ${response.contr_phone}</p>
                            <p><strong>Message:</strong> ${response.message}</p>
                            <p><strong>Pass Count:</strong> ${response.pass_count}</p>
                            <p><strong>Customer Name:</strong> ${response.cus_name}</p>
                            <p><strong>Request Date:</strong> ${response.rq_date}</p>
                            <p><strong>Start Date:</strong> ${response.fillout_st_date}</p>
                            <p><strong>End Date:</strong> ${response.fillout_end_date}</p>
                            <p><strong>Status:</strong> ${response.status}</p>
                        </div>
                    `,
                            icon: 'info',
                            customClass: {
                                htmlContainer: 'details-popup'
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error); // Debug log
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch entry pass details'
                        });
                    }
                });
            });

            function confirmAndUpdateStatus(id, status) {
                Swal.fire({
                    title: 'Confirm Update',
                    text: `Are you sure you want to ${status.toLowerCase()} this entry pass?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateEntryPassStatus(id, status);
                    }
                });
            }

            // Function to handle the status update
            function updateEntryPassStatus(id, status) {
                console.log('Updating status - ID:', id, 'Status:', status); // Debug log

                $.ajax({
                    url: 'update_fo_status.php',
                    type: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Update response:', response); // Debug log

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Reload only if update was successful
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: response.message || 'Failed to update status'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error); // Debug log
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process request. Please try again.'
                        });
                    }
                });
            }

            // Approve button click handler
            $(document).on('click', '.btn-approve', function() {
                const id = $(this).data('id');
                confirmAndUpdateStatus(id, 'Approved');
            });

            // Reject button click handler
            $(document).on('click', '.btn-reject', function() {
                const id = $(this).data('id');
                confirmAndUpdateStatus(id, 'Rejected');
            });
        });
    </script>
    <script src="../script.js"></script>
</body>

</html>