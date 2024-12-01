<?php require('../config.php');
$page_title="User Registration";  ?>
<?php if (!isset($_SESSION['staffname'])) {
    echo "<script> window.location.replace('../index.php'); </script>";
} else { ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Registration</title>
        <link rel="stylesheet" href="admin-styles.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <div class="form-container">
                <h2>User Registration Form</h2>
                <form id="user-registration-form" method="post">
                    <div class="form-group">
                        <label for="name">Full Name <span>*</span></label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender <span>*</span></label>
                        <select name="gender" id="gender" required>
                            <option value="" disabled selected>Select Your Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth (DD/MM/YYYY)</label>
                        <input type="date" id="dob" name="dob">
                    </div>
                    <div class="form-group">
                        <label for="nic">NIC Number <span>*</span></label>
                        <input type="text" id="nic" name="nic" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Permanent Address</label>
                        <input type="text" id="address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span>*</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Contact Number <span>*</span></label>
                        <input type="tel" id="tel" name="tel" required>
                    </div>
                    <div class="form-group">
                        <label for="apartment">Apartment Details</label>
                        <select id="app_no" name="app_no" class="select2-apartment" style="width: 100%;">
                            <option value="" disabled selected>Select Apartment No</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Register</button>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script>
           $(document).ready(function() {
    // Initialize Select2 for apartments
    $('.select2-apartment').select2({
        ajax: {
            url: 'fetch_open_apartments.php',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.aprt_id,
                            text: 'Block ' + item.block_no + ' Floor ' + item.floor_no + ' Unit ' + item.aprt_no
                        };
                    })
                };
            }
        }
    });

    // Form submit with AJAX
    $('#user-registration-form').on('submit', function(e) {
        e.preventDefault();

        // Show loading state
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: 'save_user.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Close loading dialog
                Swal.close();
                
                // Log the response for debugging
                console.log('Server Response:', response);
                
                if (response && response.success === true) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Registration completed successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'users.php';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'An error occurred during registration.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Close loading dialog
                Swal.close();
                
                // Log the error for debugging
                console.error('AJAX Error:', {xhr: xhr, status: status, error: error});
                
                let errorMessage = 'An unexpected error occurred.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || errorMessage;
                } catch (e) {
                    // If JSON parsing fails, use the error string
                    errorMessage = error || errorMessage;
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
        </script>
    </body>

    </html>
<?php } ?>