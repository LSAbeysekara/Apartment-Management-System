<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telephone Bills History</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .filter-container {
            margin: 20px 0;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .filter-container select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .filter-container label {
            font-weight: bold;
        }
        .view-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .view-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="form-container">
        <h2>Telephone Bills History</h2>
        
        <div class="filter-container">
            <label for="yearSelect">Year:</label>
            <select id="yearSelect"></select>
            
            <label for="monthSelect">Month:</label>
            <select id="monthSelect"></select>
        </div>

        <div class="table-wrapper">
            <table id="billsTable" class="display">
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
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Populate year dropdown
    const currentYear = new Date().getFullYear();
    const yearSelect = $('#yearSelect');
    for (let year = currentYear; year >= 2020; year--) {
        yearSelect.append(`<option value="${year}">${year}</option>`);
    }

    // Populate month dropdown
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    const monthSelect = $('#monthSelect');
    const currentMonth = new Date().getMonth();
    
    months.forEach((month, index) => {
        const option = $(`<option value="${month}">${month}</option>`);
        if (yearSelect.val() == currentYear && index > currentMonth) {
            option.prop('disabled', true);
        }
        monthSelect.append(option);
    });

    // Initialize DataTable
    const table = $('#billsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: 'get_bills.php',
            data: function(d) {
                d.year = $('#yearSelect').val();
                d.month = $('#monthSelect').val();
                d.bill_type = 'Telephone';
            }
        },
        columns: [
            { data: 'aprt_id' },
            { data: 'cus_id' },
            { data: 'cus_name' },
            { data: 'bill_month' },
            { data: 'outstanding' },
            { data: 'amount' },
            { data: 'message' },
            {
                data: null,
                render: function(data, type, row) {
                    return '<button class="view-btn" onclick="viewDetails(\'' + 
                           data.bill_id + '\', \'' + 
                           data.bill_month + '\')">View</button>';
                }
            }
        ]
    });

    // Handle year/month change
    $('#yearSelect, #monthSelect').change(function() {
        const selectedYear = parseInt($('#yearSelect').val());
        const currentYear = new Date().getFullYear();
        const currentMonth = new Date().getMonth();

        // Disable future months if current year is selected
        if (selectedYear === currentYear) {
            $('#monthSelect option').each(function(index) {
                $(this).prop('disabled', index > currentMonth);
            });
        } else {
            $('#monthSelect option').prop('disabled', false);
        }

        table.ajax.reload();
    });
});

function viewDetails(billId, billMonth) {
    // AJAX call to get detailed information
    $.ajax({
        url: 'get_bill_details.php',
        method: 'POST',
        data: { 
            bill_id: billId,
            bill_month: billMonth,
            bill_type: 'Telephone'
        },
        success: function(response) {
            const data = JSON.parse(response);
            Swal.fire({
                title: 'Bill Details',
                html: `
                    <div style="text-align: left">
                        <p><strong>Customer ID:</strong> ${data.cus_id}</p>
                        <p><strong>Customer Name:</strong> ${data.cus_name}</p>
                        <p><strong>Apartment ID:</strong> ${data.aprt_id}</p>
                        <p><strong>Bill Month:</strong> ${data.bill_month}</p>
                        <p><strong>Amount:</strong> Rs. ${data.amount}</p>
                        <p><strong>Total Outstanding:</strong> Rs. ${data.outstanding}</p>
                      
                        <p><strong>Message:</strong> ${data.message}</p>
                        <p><strong>Created Date:</strong> ${data.crt_date}</p>
                        <p><strong>Created By:</strong> ${data.crt_by}</p>
                    </div>
                `,
                width: '600px',
                confirmButtonText: 'Close'
            });
        },
        error: function() {
            Swal.fire('Error', 'Failed to fetch bill details', 'error');
        }
    });
}
</script>

</body>
</html>