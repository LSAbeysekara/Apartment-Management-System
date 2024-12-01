<?php include('../config.php'); ?><!DOCTYPE html>
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
  <?php unset($_SESSION['form_error']);}?>
    <div class="main-content">
        <div class="form-container">
            <h2>Vehicle Pass History</h2>
            <section class="tables-section">
            <div class="table-wrapper">
                <h2>Pending Vehicle Pass Requests</h2>
                <table class="styled-table" id="pmaid">
                    <thead>
                        <tr>
                            <th>Apartment ID</th>
                            <th>V ID</th>
                            <th>Vehicle Number</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle Color</th>
                            <th>Parking Spot</th>
                            <th>Customer Name</th>
                            <th>Req. Date</th>
                            <th>Approved Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                $query = "SELECT *, c.cus_name 
                          FROM tbl_vehicle a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id where a.status ='Pending' order by a.req_date desc";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        
                        echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['v_id']}</td>
                                <td>{$row['v_number']}</td>
                                <td>{$row['v_type']}</td>
                                <td>{$row['v_color']}</td>
                                 <td>{$row['p_spot']}</td>
                                <td>{$row['cus_name']}</td>
                                <td>{$row['req_date']}</td>
                                <td>{$row['app_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view' id='pbview' data-id='{$row['v_id']}'>View</button>
                                    <button class='btn-approve' data-id='{$row['v_id']}'>Approved</button>
                                    <button class='btn-reject' data-id='{$row['v_id']}'>Reject</button> 
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
                            <th>V ID</th>
                            <th>Vehicle Number</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle Color</th>
                            <th>Parking Spot</th>
                            <th>Customer Name</th>
                            <th>Req. Date</th>
                            <th>Approved Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                $query = "SELECT *, c.cus_name 
                          FROM tbl_vehicle a 
                          LEFT JOIN tbl_customer c ON a.cus_id = c.cus_id where a.status !='Pending' order by a.req_date desc";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        
                        echo "<tr>
                                <td>{$row['aprt_id']}</td>
                                <td>{$row['v_id']}</td>
                                <td>{$row['v_number']}</td>
                                <td>{$row['v_type']}</td>
                                <td>{$row['v_color']}</td>
                                 <td>{$row['p_spot']}</td>
                                <td>{$row['cus_name']}</td>
                                <td>{$row['req_date']}</td>
                                <td>{$row['app_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='button-container'>
                                    <button class='btn-view' id='bview' data-id='{$row['v_id']}'>View</button>

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
    // Initialize DataTables for tables
    $('#pmaid').DataTable();
    $('#maid').DataTable();

    $('.btn-view').off('click');
    $('.btn-approve').off('click');
    $('.btn-reject').off('click');
    // View Button - Display Details in SweetAlert
    $(document).on('click', '.btn-view', function() {
        const id = $(this).data('id');
        console.log('Clicked View Button - ID:', id); // Debug log
        
        $.ajax({
            url: 'get_v_details.php',
            type: 'POST',
            data: { id: id },
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
                    title: 'Vehicle Pass Details',
                    html: `
                        <div class="details-container">
                            <p><strong>Apartment ID:</strong> ${response.aprt_id}</p>
                            <p><strong>V ID:</strong> ${response.v_id}</p>
                            <p><strong>Vehicle No:</strong> ${response.v_number}</p>
                            <p><strong>Vehicle Type:</strong> ${response.v_type}</p>
                            <p><strong>Vehicle Color:</strong> ${response.v_color}</p>
                            <p><strong>Parking Spot:</strong> ${response.person_count}</p>
                            <p><strong>Customer Name:</strong> ${response.p_spot}</p>
                            <p><strong>Request Date:</strong> ${response.req_date}</p>
                            <p><strong>Approved Date:</strong> ${response.app_date}</p>
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
                if(status === 'Approved') {
                    showSpotSelectionPopup(id);
                } else {
                    updateStatus(id, status);
                }
            }
        });
    }

    $(document).on('click', '.btn-approve', function() {
        const id = $(this).data('id');
        confirmAndUpdateStatus(id, 'Approved');
    });

    $(document).on('click', '.btn-reject', function() {
        const id = $(this).data('id');
        confirmAndUpdateStatus(id, 'Rejected');
    });

    function updateStatus(id, status, pSpot = null) {
        $.ajax({
            url: 'update_vehicle_status.php',
            type: 'POST',
            data: {
                v_id: id,
                status: status,
                p_spot: pSpot
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',  // This icon should reflect the success status
                            title: result.message || `Vehicle pass ${status.toLowerCase()} successfully!`,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: result.message || 'Failed to update vehicle pass status!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'System error occurred',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed to connect to server',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
    function showSpotSelectionPopup(id) {
        $.ajax({
            url: 'get_taken_spots.php',
            type: 'GET',
            success: function(takenSpots) {
                let availableSpots = [];
                for (let i = 1; i <= 50; i++) {
                    let spot = `P${String(i).padStart(2, '0')}`;
                    if (!takenSpots.includes(spot)) {
                        availableSpots.push(spot);
                    }
                }

                Swal.fire({
                    title: 'Select a Parking Spot',
                    html: `
                        <select id="parking-spot">
                            ${availableSpots.map(spot => `<option value="${spot}">${spot}</option>`).join('')}
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const selectedSpot = document.getElementById('parking-spot').value;
                        updateStatus(id, 'Approved', selectedSpot);
                    }
                });
            }
        });
    }
});
    </script>
</body>
</html>
