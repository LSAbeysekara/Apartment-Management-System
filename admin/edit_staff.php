<?php
require('../config.php');
$page_title = "Edit Staff";
if (!isset($_SESSION['staffname'])) {
    echo "<script> window.location.replace('../index.php'); </script>";
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch current staff details from the database
        $stmt = $conn->prepare("SELECT * FROM tbl_employee WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_assoc();

        if (!$staff) {
            echo "<script>window.location.replace('staff.php');</script>";
            exit();
        }

        // Closing the prepared statement
        $stmt->close();
    } else {
        echo "<script>window.location.replace('staff.php');</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff - <?php echo $staff['emp_name']; ?></title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2>Edit Staff - <?php echo $staff['emp_name']; ?></h2>
            <form id="editStaffForm" method="POST" action="update_staff.php">
                <input type="hidden" name="id" value="<?php echo $staff['id']; ?>">
                <div class="form-group">
                    <label for="emp_name">Full Name</label>
                    <input type="text" name="emp_name" id="emp_name" value="<?php echo $staff['emp_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="emp_phone">Phone Number</label>
                    <input type="text" name="emp_phone" id="emp_phone" value="<?php echo $staff['emp_phone']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="emp_email">Email</label>
                    <input type="email" name="emp_email" id="emp_email" value="<?php echo $staff['emp_email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="emp_nic">NIC Number</label>
                    <input type="text" name="emp_nic" id="emp_nic" value="<?php echo $staff['emp_nic']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="emp_username">Username</label>
                    <input type="text" name="emp_username" id="emp_username" value="<?php echo $staff['emp_username']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="emp_type">Employee Type</label>
                    <select name="emp_type" id="emp_type" required>
                        <option value="admin" <?php echo $staff['emp_type'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="emp" <?php echo $staff['emp_type'] == 'emp' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="Active" <?php echo $staff['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Inactive" <?php echo $staff['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Update Staff</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#editStaffForm').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: 'update_staff.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    let res = JSON.parse(response);
                    Swal.fire({
                        title: res.success ? 'Success!' : 'Error!',
                        text: res.message,
                        icon: res.success ? 'success' : 'error'
                    }).then(() => {
                        if (res.success) {
                            window.location.href = 'staff.php';
                        }
                    });
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while updating staff details.', 'error');
                }
            });
        });
    });
    </script>
</body>
</html>
