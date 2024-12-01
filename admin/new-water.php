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
    <style>
        .header-container {
            display: flex;
            align-items: center; /* Center items vertically */
            justify-content: center; /* Center h2 horizontally */
            position: relative; /* Allows absolute positioning of the button */
            margin-bottom: 20px;
        }

        .header-container h2 {
            flex: 1; /* Allows the h2 to take up available space */
            text-align: center;
        }

        .header-container a {
            position: absolute;
            right: 0; /* Aligns button to the top right corner of the container */
            text-decoration: none; /* Removes underline from the link */
        }

        .new-request-button {
            background-color: #5e2b97;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: relative;
        }

        .new-request-button:hover {
            background-color: #411f69;
            transform: scale(1.05);
        }

        .badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: #ff6347;
            color: white;
            padding: 3px 7px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }

    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="form-container">
        <div class="table-wrapper">
            <div class="header-container">
                <h2>Water Bills</h2>
                <a href="new-request-water.php"><button class="new-request-button">
                    New Requests

                    <?php
                    $sql = "SELECT COUNT(*) AS request_count FROM tbl_customer WHERE Water = 'request'";

                    // Execute the query
                    $result = $conn->query($sql);

                    // Check if the query was successful
                    if ($result) {
                        // Fetch the count result
                        $row = $result->fetch_assoc();
                        $requestCount = $row['request_count'];  // Get the count of requests 
                        
                        if($requestCount > 0) {
                        ?>
                        <span class="badge"><?php echo $requestCount; ?></span>
                    <?php
                        }
                    }
                    ?>

                </button></a>
            </div>
            
            <table class="styled-table" id="maid">
                <thead>
                    <tr>
                        <th>Apartment ID</th>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Bill Month</th>
                        <th>Outstanding</th>
                        <th>Amount</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here by JavaScript -->
                </tbody>
            </table>
            <button id="submit-all" class="submit-button">Submit All</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    const currentMonth = new Date().toLocaleString('default', { month: 'long' });
    const currentYear = new Date().getFullYear();
    const currentMonthYear = `${currentMonth} ${currentYear}`;
    const billType = 'Water';

    // Initialize DataTable
    const table = $('#maid').DataTable();

    // Fetch Data with AJAX
    function fetchData() {
        $.ajax({
            url: 'fetch_w_bills.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                table.clear();
                data.forEach(row => {
                    // Check if customer has bill for current month and bill type
                    const hasExistingBill = row.bill_id && row.bill_type === billType && row.bill_month === currentMonthYear;
                    const billId = row.bill_id || '';
                    
                    // Check if outstanding exists for this bill type
                    const outstanding = row.outstanding || 0;
                    const isDisabled = hasExistingBill;

                    table.row.add([
                        row.aprt_id,
                        row.cus_id,
                        row.cus_name,
                        `<span>${currentMonthYear}</span>`,
                        outstanding > 0 ? `${outstanding}` : "No",
                        `<input type="number" ${isDisabled ? 'disabled' : ''} value="${row.amount || ''}" data-cus-id="${row.cus_id}" class="amount-input">`,
                        `<input type="text" ${isDisabled ? 'disabled' : ''} value="${row.message || ''}" data-cus-id="${row.cus_id}" class="message-input">`,
                        `<div class="button-group">
                            ${!isDisabled ? 
                                `<button class="btn-save" data-cus-id="${row.cus_id}">Save</button>` :
                                `<button class="btn-edit" data-cus-id="${row.cus_id}" data-bill-id="${billId}">Edit</button>`
                            }
                         </div>`
                    ]).draw();
                });
            }
        });
    }

    // Load initial data
    fetchData();

    // Handle Edit button
    $('#maid').on('click', '.btn-edit', function() {
        const cusId = $(this).data('cus-id');
        const billId = $(this).data('bill-id');
        const row = $(this).closest('tr');
        
        const currentAmount = row.find('.amount-input').val();
        const currentMessage = row.find('.message-input').val();
        const currentOutstanding = row.find('td:eq(4)').text();

        Swal.fire({
            title: 'Edit Water Bill',
            html: `
                <div class="swal2-form">
                    <label>Amount:</label>
                    <input id="swal-amount" class="swal2-input" type="number" value="${currentAmount}">
                    <label>Message:</label>
                    <input id="swal-message" class="swal2-input" type="text" value="${currentMessage}">
                    <label>Current Outstanding: ${currentOutstanding}</label>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Changes',
            preConfirm: () => {
                return {
                    amount: document.getElementById('swal-amount').value,
                    message: document.getElementById('swal-message').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const newAmount = result.value.amount;
                const newMessage = result.value.message;
                
                $.post('save_w_bill.php', {
                    cus_id: cusId,
                    edit_bill_id: billId,
                    amount: newAmount,
                    message: newMessage,
                    current_amount: currentAmount,
                    bill_month: currentMonthYear,
                    aprt_id: row.find('td:first').text()
                }, function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        fetchData(); // Refresh data after saving
                    });
                }, 'json')
                .fail(function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update bill. Please try again.'
                    });
                });
            }
        });
    });

    // Handle individual Save button
    $('#maid').on('click', '.btn-save', function () {
        const cusId = $(this).data('cus-id');
        const amount = $(`input.amount-input[data-cus-id="${cusId}"]`).val();
        const message = $(`input.message-input[data-cus-id="${cusId}"]`).val();
        const aprtId = $(this).closest('tr').find('td:first').text();

        if (!amount) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter an amount'
            });
            return;
        }

        $.post('save_w_bill.php', { 
            cus_id: cusId, 
            amount, 
            message, 
            aprt_id: aprtId, 
            bill_month: currentMonthYear 
        }, function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            }).then(() => {
                fetchData();
            });
        }, 'json')
        .fail(function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save bill. Please try again.'
            });
        });
    });

    // Handle Submit All button
    $('#submit-all').click(function () {
        const bills = [];
        let hasEmptyAmount = false;

        table.rows().every(function () {
            const row = this.node();
            const amountInput = $(row).find('.amount-input');
            
            if (!amountInput.prop('disabled')) {
                const amount = amountInput.val();
                if (!amount) {
                    hasEmptyAmount = true;
                    return false; // Break the loop
                }

                bills.push({ 
                    cus_id: amountInput.data('cus-id'), 
                    amount: amount, 
                    message: $(row).find('.message-input').val(), 
                    aprt_id: $(row).find('td:first').text(), 
                    bill_month: currentMonthYear 
                });
            }
        });

        if (hasEmptyAmount) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter all bill amounts before submitting'
            });
            return;
        }

        if (bills.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: 'No new bills to submit'
            });
            return;
        }

        $.post('save_w_all_bills.php', { bills }, function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            }).then(() => {
                fetchData();
            });
        }, 'json')
        .fail(function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to submit bills. Please try again.'
            });
        });
    });
});
</script>
<script src="../script.js"></script>
</body>
</html>