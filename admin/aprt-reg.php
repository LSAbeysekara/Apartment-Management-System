
<?php require('../config.php'); ?>
<?php if (!isset($_SESSION['staffname'])) {
  echo "<script> window.location.replace('../index.php'); </script>";
} else { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Registration</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="form-container">
            <h2>Apartment Registration Form</h2>
            <form id="apartmentForm">
                <div class="form-group">
                    <label for="Apartment">Building Details</label>
                    <div class="form-inline-group">
                        <input type="number" id="block" name="block" placeholder="Block Number" required>
                        <input type="number" id="floor" name="floor" placeholder="Floor Number" required>
                        <input type="number" id="unit" name="unit" placeholder="Unit Number" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="room">Room count</label>
                    <input type="number" id="room" name="room" min="0" required>
                </div>
                <div class="form-group">
                    <label for="bathroom">Bathroom count</label>
                    <input type="number" id="bathroom" name="bathroom" min="0" required>
                </div>
                <div class="form-group">
                    <label for="area">Area (sqft)</label>
                    <input type="number" id="area" name="area" min="0" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="open">Open</option>
                        <option value="occupied">Occupied</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Register</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script.js"></script>
    <script>
        $(document).ready(function() {
            $('#apartmentForm').on('submit', function(e) {
                e.preventDefault();

                let block = $('#block').val();
                let floor = $('#floor').val();
                let unit = $('#unit').val();
                let room = $('#room').val();
                let bathroom = $('#bathroom').val();
                let area = $('#area').val();
                let status = $('#status').val();
                
                // Generate aprt_id
                let aprt_id = `B${block}F${floor}U${unit}`;

                $.ajax({
                    url: 'save_apartment.php',
                    type: 'POST',
                    data: {
                        aprt_id: aprt_id,
                        block: block,
                        floor: floor,
                        unit: unit,
                        room: room,
                        bathroom: bathroom,
                        area: area,
                        status: status
                    },
                    success: function(response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registered!',
                                text: 'Apartment registered successfully.'
                            }).then(() => {
                                $('#apartmentForm')[0].reset();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'There was an issue registering the apartment.'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php } ?>
