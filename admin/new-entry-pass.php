<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
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
<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2>Application for New Entry Pass</h2>
            <form action="add-entry-pass.php" method="post">
            <div class="form-group-entry-pass">
                    <label for="Apartment">Apartment Owner's Details</label>
                    <div class="form-inline-group">
                        <select id="aprt_id" name="aprt_id" class="select2" style="width:fit-content" required>
                            <option value="" disabled selected>Select Apartment ID</option>
                        </select>
                        <input type="text" id="cus_username" name="cus_username" placeholder="Owner's Username" required>
                        <input type="hidden" id="cus_id" name="cus_id" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Name of the Visitor</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="tel">Phone Number</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-row-entry-pass">
                    <div class="form-group-entry-pass">
                        <label for="nic">NIC</label>
                        <input type="text" id="nic" name="nic" required>
                    </div>
                    <div class="form-group-entry-pass">
                        <label for="count">Person Count</label>
                        <input type="number" id="p_count" name="p_count" min="0" required>
                    </div>
                </div>
                <div class="form-row-entry-pass">
                    <div class="form-group-entry-pass">
                        <label for="s_date">Start Date</label>
                        <input type="date" id="s_date" name="s_date" required>
                    </div>
                    <div class="form-group-entry-pass">
                        <label for="e_date">End Date</label>
                        <input type="date" id="e_date" name="e_date" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pass_type">Entry Pass Type</label>
                    <select name="pass_type" id="pass_type" required>
                        <option value="" disabled selected>Select Entry Pass Type</option>
                        <option value="ds">Domestic Servant</option>
                        <option value="driver">Driver</option>
                        <option value="laundry">Laundry</option>
                        <option value="vendor">Vendor</option>
                        <option value="agent">Agent</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group-entry-pass">
                    <label for="pass_type">Person's Picture</label>
                    <input type="file" name="image" id="image">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Denied">Denied</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Load apartments with status 'Occupied' in the dropdown
            $.ajax({
                url: 'get_apartments.php', 
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(function(item) {
                        $('#aprt_id').append(new Option(item.aprt_id, item.aprt_id));
                    });
                }
            });

            // Fetch and display customer details when an apartment is selected
            $('#aprt_id').change(function() {
                var aprt_id = $(this).val();
                $.ajax({
                    url: 'get_customer.php',
                    type: 'GET',
                    data: { aprt_id: aprt_id },
                    dataType: 'json',
                    success: function(response) {
                        $('#cus_username').val(response.cus_name);
                        $('#cus_id').val(response.cus_id);
                        $('#cus_id').val(response.cus_id);
                    }
                });
            });
        });
    </script>
    <script src="../script.js"></script>
</body>
</html>
