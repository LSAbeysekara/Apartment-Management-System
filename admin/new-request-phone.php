<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .header-container {
            display: flex;
            align-items: center; /* Align items vertically in the center */
            justify-content: center; /* Center the h2 horizontally */
            position: relative; /* Allows positioning of the back link */
            margin-bottom: 20px;
        }

        .header-container h2 {
            flex: 1; /* Center the h2 by taking available space */
            text-align: center;
        }

        .back-link {
            position: absolute;
            left: 0; /* Aligns link to the top left corner */
            color: #007BFF; /* Blue color for the link */
            font-size: 16px;
            text-decoration: none; /* Removes underline */
            padding: 5px 10px;
        }

        .back-link:hover {
            text-decoration: underline; /* Underline on hover for emphasis */
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="form-container">
        <div class="table-wrapper">
            <div class="header-container">
                <a href="new-telephone.php" class="back-link"><span class="material-symbols-outlined">arrow_back</span></a>
            </div>

            <?php
            $sql = "SELECT COUNT(*) AS request_count FROM tbl_customer WHERE Telephone = 'request'";

            // Execute the query
            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result) {
                // Fetch the count result
                $row = $result->fetch_assoc();
                $requestCount = $row['request_count'];  // Get the count of requests 
                
                if($requestCount > 0) {
                ?>
                <h2>Telephone New Requests</h2>

                <table class="styled-table" id="maid">
                    <thead>
                        <tr>
                            <th>Apartment ID</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data for requests will be populated here by JavaScript -->
                    </tbody>
                </table>
                <button id="submit-all" class="submit-button">Approve All</button>
            <?php
                }
            }
            ?>
        </div>

        <!-- New Table for Non-Requesting / Non-Joined Accounts -->
        <div class="table-wrapper">
            <div class="header-container">
                <h2>Add New Telephone Account</h2>
            </div>

            <table class="styled-table" id="non-joined">
                <thead>
                    <tr>
                        <th>Apartment ID</th>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data for non-joined accounts will be populated here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


<!-- This is the js part which shows who didn't joined to this bill type and join them -->
<script>
    $(document).ready(function () {
    const currentMonth = new Date().toLocaleString('default', { month: 'long' });
    const currentYear = new Date().getFullYear();
    const currentMonthYear = `${currentMonth} ${currentYear}`;
    const billType = 'Telephone';

    // Initialize DataTables
    const requestTable = $('#maid').DataTable();
    const nonJoinedTable = $('#non-joined').DataTable();

    // Fetch Data for Requests
    function fetchRequests() {
        $.ajax({
            url: 'fetch_e_bills_phone.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                requestTable.clear();
                data.forEach(row => {
                    const billId = row.bill_id || '';
                    requestTable.row.add([
                        row.aprt_id,
                        row.cus_id,
                        row.cus_name,
                        `<div class="button-group">
                            <button class="btn-save" data-cus-id="${row.cus_id}">Approve</button>
                            <button class="btn-cancel" data-cus-id="${row.cus_id}">Deny</button>
                        </div>`
                    ]).draw();
                });
            }
        });
    }

    // Handle Approve button click for individual requests
    $(document).on('click', '.btn-save', function () {
        const cusId = $(this).data('cus-id');  // Get customer ID

        $.ajax({
            url: 'approve-request-phone.php',  // PHP file that will handle the approval
            method: 'POST',
            data: { cus_id: cusId, bill_type: 'Telephone' },
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                fetchRequests();  // Reload requests after approval
                window.location.reload();  // Reload the page to reflect changes
            },
            error: function () {
                alert('Error with the request approval.');
            }
        });
    });

    
    // Handle Approve All button click
    $('#submit-all').on('click', function () {
        if (confirm('Are you sure you want to approve all requests?')) {
            $.ajax({
                url: 'approve-request-phone-all.php',  // PHP file that will handle the approval for all
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    fetchRequests();  // Reload requests after approval
                    window.location.reload();  // Reload the page to reflect changes
                },
                error: function () {
                    alert('Error with the approve all request.');
                }
            });
        }
    });


    // Handle Deny button click for individual requests
    $(document).on('click', '.btn-cancel', function () {
        const cusId = $(this).data('cus-id');  // Get customer ID

        if (confirm('Are you sure you want to deny this request?')) {
            $.ajax({
                url: 'deny-request-phone.php',  // PHP file that will handle the denial
                method: 'POST',
                data: { cus_id: cusId, bill_type: 'Telephone' },
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    fetchRequests();  // Reload requests after denial
                    window.location.reload();  // Reload the page to reflect changes
                },
                error: function () {
                    alert('Error with the request denial.');
                }
            });
        }
    });



    // Fetch Data for Non-Joined Accounts
    function fetchNonJoinedAccounts() {
        $.ajax({
            url: 'fetch_e_non_bills_phone.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                nonJoinedTable.clear();
                data.forEach(row => {
                    nonJoinedTable.row.add([
                        row.aprt_id,
                        row.cus_id,
                        row.cus_name,
                        `<button class="btn-add" data-cus-id="${row.cus_id}">Add</button>`
                    ]).draw();
                });
            }
        });
    }

    // Load initial data
    fetchRequests();
    fetchNonJoinedAccounts();

    // Handle Add to Telephone button click
    $('#non-joined').on('click', '.btn-add', function () {
        const cusId = $(this).data('cus-id');
        const billType = 'Telephone'; // Define bill type here

        $.ajax({
            url: 'add_to_phone.php',
            method: 'POST',
            data: { cus_id: cusId, bill_type: billType },  // Send bill_type data
            dataType: 'json',  // Expecting a JSON response from PHP
            success: function (response) {
                if (response.message) {
                    alert(response.message);  // Show the message from the PHP response
                    window.location.reload();  // Reload the page to reflect changes
                } else {
                    alert('Unexpected error occurred.');
                }
            },
            error: function () {
                alert('Error with the AJAX request.');
            }
        });
    });


});

</script>

<script src="../script.js"></script>
</body>
</html>