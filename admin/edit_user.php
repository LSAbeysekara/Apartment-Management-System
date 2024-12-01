
<?php require('../config.php'); ?>
<?php if (!isset($_SESSION['staffname'])) {
    echo "<script> window.location.replace('../index.php'); </script>";
} else { 
    if (!isset($_GET['id'])) {
        echo "<script> window.location.replace('users.php'); </script>";
        exit;
    }

    $id = $_GET['id'];
    $query = "SELECT cus_id, cus_name, cus_gender, cus_nic, cus_email, cus_phone, apartment_id FROM tbl_customer WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        echo "<script> window.location.replace('users.php'); </script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Edit User Details</h2>
        <form id="editUserForm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="cus_id">Customer ID</label>
                <input type="text" id="cus_id" name="cus_id" value="<?php echo $user['cus_id']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="cus_name">Full Name</label>
                <input type="text" id="cus_name" name="cus_name" value="<?php echo $user['cus_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cus_gender">Gender</label>
                <select name="cus_gender" id="cus_gender" required>
                    <option value="male" <?php echo ($user['cus_gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($user['cus_gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cus_nic">NIC Number</label>
                <input type="text" id="cus_nic" name="cus_nic" value="<?php echo $user['cus_nic']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cus_email">Email</label>
                <input type="email" id="cus_email" name="cus_email" value="<?php echo $user['cus_email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cus_phone">Contact Number</label>
                <input type="tel" id="cus_phone" name="cus_phone" value="<?php echo $user['cus_phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="apartment_id">Apartment</label>
                <select name="apartment_id" id="apartment_id" style="width: 100%;" required>
                    <!-- Option loading handled by AJAX and Select2 -->
                </select>
            </div>
            <button type="button" class="submit-btn" onclick="updateUser()">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
$(document).ready(function() {
    // Add the current apartment as the initial selected option
    const currentApartmentId = '<?php echo $user['apartment_id']; ?>';
    const currentApartmentText = '<?php echo $user['apartment_id'] ? $user['apartment_id'] : 'None'; ?>'; // Adjust as needed if 'None' is shown when empty

    if (currentApartmentId) {
        let option = new Option(currentApartmentText, currentApartmentId, true, true);
        $('#apartment_id').append(option).trigger('change');
    }

    // Initialize Select2 with AJAX loading for open apartments
    $('#apartment_id').select2({
        ajax: {
            url: 'get_open_apartments.php',
            dataType: 'json',
            processResults: function(data) {
                return {
                    results: data.map(apartment => ({
                        id: apartment.id,
                        text: apartment.aprt_id
                    }))
                };
            }
        }
    });
});

        function updateUser() {
            $.ajax({
                url: 'update_user.php',
                type: 'POST',
                data: $('#editUserForm').serialize(),
                success: function(response) {
                    let data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'User details have been updated successfully.',
                        }).then(() => {
                            window.location.replace('users.php');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to update user details.',
                        });
                    }
                }
            });
        }
    </script>
</body>
</html>
<?php } ?>
