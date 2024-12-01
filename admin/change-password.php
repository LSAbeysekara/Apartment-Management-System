<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <form id="passwordForm" method="post" enctype="multipart/form-data" class="form-container-prof">
        <div class="topic" style="border:none;">
            <h2>Change Password</h2>
        </div>

        <div class="form-group">
            <label for="cpassword">Current Password</label>
            <div class="input-wrapper">
                <input type="password" name="cpassword" id="cpassword" required>
                <span class="material-symbols-outlined toggle-visibility" onclick="togglePasswordVisibility('cpassword', this)">visibility</span>
            </div>
        </div>

        <div class="form-group">
            <label for="npassword">New Password</label>
            <div class="input-wrapper">
                <input type="password" name="npassword" id="npassword" required>
                <span class="material-symbols-outlined toggle-visibility" onclick="togglePasswordVisibility('npassword', this)">visibility</span>
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_npassword">Confirm Password</label>
            <div class="input-wrapper">
                <input type="password" name="confirm_npassword" id="confirm_npassword" required>
                <span class="material-symbols-outlined toggle-visibility" onclick="togglePasswordVisibility('confirm_npassword', this)">visibility</span>
            </div>
        </div>

        <div id="editButtons" class="button-container" style="margin-top: 10px;">
            <div class="submit-cancel-group">
                <button type="submit" class="submit-btn">Change Password</button>
            </div>
        </div>
    </form>
</div>

<script>
function togglePasswordVisibility(fieldId, icon) {
    const passwordField = document.getElementById(fieldId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.textContent = "visibility_off";
    } else {
        passwordField.type = "password";
        icon.textContent = "visibility";
    }
}

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the form from submitting normally

    const formData = new FormData(this);

    fetch('edit-password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: data.status === 'success' ? 'success' : 'error',
            title: data.message,
            showConfirmButton: false,
            timer: 1500
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'An error occurred. Please try again.',
            showConfirmButton: false,
            timer: 1500
        });
    });
});
</script>
</body>
</html>
