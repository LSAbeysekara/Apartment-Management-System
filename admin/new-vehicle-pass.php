<?php include '../config.php'; ?>
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
    <?php include 'sidebar.php'; ?>
    <?php if (isset($_SESSION['form_success'])) { ?>
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "<?php echo $_SESSION['form_success']; ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php unset($_SESSION['form_success']);
    } ?>

    <?php if (isset($_SESSION['form_error'])) { ?>
        <script>
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "<?php echo $_SESSION['form_error']; ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php unset($_SESSION['form_error']);
    } ?>
    <div class="main-content">
        <div class="form-container">
            <h2>Application for Vehicle Pass</h2>
            <form action="add-vehicle-pass.php" method="post">
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
                <div class="form-row-entry-pass">
                    <div class="form-group-entry-pass">
                        <label for="v_number">Vehicle Number</label>
                        <input type="text" id="v_number" name="v_number" required>
                    </div>
                    <div class="form-group-entry-pass">
                        <label for="v_color">Vehicle Color</label>
                        <input type="text" id="v_color" name="v_color" required>
                    </div>
                </div>
                <div class="form-row-entry-pass">
                    <div class="form-group-entry-pass">
                        <label for="v_type">Vehicle Type</label>
                        <select name="v_type" id="v_type" required>
                            <option value="" disabled selected>Select Vehicle Type</option>
                            <option value="Car">Car</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Bicycle">Bicycle</option>
                            <option value="Truck">Truck</option>
                            <option value="Bus">Bus</option>
                            <option value="Van">Van</option>
                            <option value="Tractor">Tractor</option>
                        </select>
                    </div>
                    <div class="form-group-entry-pass">
                        <label for="p_spot">Parking Spot</label>
                        <select id="p_spot" name="p_spot" required>
                            <option value="" disabled selected>Select Parking Spot</option>
                            <!-- Options will be populated via AJAX -->
                        </select>
                    </div>
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

            // Load available parking spots in the dropdown
            function loadParkingSpots() {
                $.ajax({
                    url: 'get_taken_spots.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(takenSpots) {
                        let availableSpots = [];
                        for (let i = 1; i <= 50; i++) {
                            let spot = `P${String(i).padStart(2, '0')}`;
                            if (!takenSpots.includes(spot)) {
                                availableSpots.push(spot);
                            }
                        }

                        $('#p_spot').empty(); // Clear any existing options
                        availableSpots.forEach(function(spot) {
                            $('#p_spot').append(new Option(spot, spot));
                        });
                    }
                });
            }

            // Call the function when the document is ready to populate the dropdown
            $(document).ready(function() {
                loadParkingSpots();
            });
            // Fetch and display customer details when an apartment is selected
            $('#aprt_id').change(function() {
                var aprt_id = $(this).val();
                $.ajax({
                    url: 'get_customer.php',
                    type: 'GET',
                    data: {
                        aprt_id: aprt_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#cus_username').val(response.cus_name);
                        $('#cus_id').val(response.cus_id);
                    }
                });
            });
        });
    </script>
    <script src="../script.js"></script>
</body>

</html>