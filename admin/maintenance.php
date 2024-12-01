<?php

require('../config.php');

// Redirect if not logged in
if (!isset($_SESSION['staffname'])) {
    echo "<script> window.location.replace('../index.php'); </script>";
    exit();
}

// Fetch pending maintenance requests with customer names
$pendingRequestsQuery = "
    SELECT tm.*, tc.cus_name 
    FROM tbl_maintenance tm
    JOIN tbl_customer tc ON tm.cus_id = tc.cus_id
    WHERE tm.status = 'pending'";
$pendingRequests = mysqli_query($conn, $pendingRequestsQuery);

// Fetch all maintenance history with customer names
$historyRequestsQuery = "
    SELECT tm.*, tc.cus_name 
    FROM tbl_maintenance tm
    JOIN tbl_customer tc ON tm.cus_id = tc.cus_id
    WHERE tm.status != 'pending'";
$historyRequests = mysqli_query($conn, $historyRequestsQuery);

function getMaintenanceImages($main_id) {
    // Use absolute path from server root
    $imageDir = realpath("../assets/images/maintenance/$main_id");
    
    // Check if the directory exists
    if (!$imageDir || !is_dir($imageDir)) {
        return []; // Return empty array if directory doesn't exist
    }

    // Scan the directory for all files
    $files = scandir($imageDir);

    // Allowed image extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    
    $images = [];
    foreach ($files as $file) {
        
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($fileExtension, $allowedExtensions)) {
            // Construct URL-friendly path relative to web root
            $webPath = "../assets/images/maintenance/$main_id/" . basename($file);
            $images[] = $webPath;
        }
    }

    // Limit to 3 images
    return array_slice($images, 0, 3);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <header>
        <h1>New Maintenance Requests</h1>
    </header>
    
    <div class="dashboard" style="grid-template-columns: repeat(3, minmax(200px, 1fr)); margin-bottom:3vw;">
        <?php while($request = mysqli_fetch_assoc($pendingRequests)): ?>
            <div class="card" style="padding: 2px;" id="colors">
                <div style="border-radius:15px; padding: 5px;" >
                    <table>
                        <tr>
                            <td style="font-size:1rem; font-weight:bold;">Aprt ID:</td>
                            <td style="padding:5px;"><?= $request['aprt_id'] ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:1rem; font-weight:bold;">Name:</td>
                            <td style="padding:5px;"><?= $request['cus_name'] ?></td>
                        </tr>
                    </table>
                    <div class="form-container">
                        <?= htmlspecialchars($request['message']) ?>
                    </div>
                    <div class="form-container">
                    <?php
    $images = getMaintenanceImages($request['main_id']);
    if (!empty($images)) {
        foreach ($images as $imagePath): ?>
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Maintenance image" style="max-width:100px; margin-right:5px;">
        <?php endforeach;
    } else {
        echo "No images available.";
    }
    ?>
                    </div>
                    <div class="status-select">
                        <select name="status" data-id="<?= $request['main_id'] ?>" class="status-dropdown" id="status1">
                            <option value="pending" <?= $request['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="working_on" <?= $request['status'] == 'working_on' ? 'selected' : '' ?>>Working On</option>
                            <option value="assigned" <?= $request['status'] == 'assigned' ? 'selected' : '' ?>>Assigned</option>
                            <option value="completed" <?= $request['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <header>
        <h1>Maintenance History</h1>
    </header>
    
    <div class="dashboard" style="grid-template-columns: repeat(3, minmax(200px, 1fr)); margin-bottom:3vw;">
        <?php while($request = mysqli_fetch_assoc($historyRequests)): ?>
            <div class="card" style="padding: 2px;">
                <div style="border-radius:15px; padding: 5px;" id="colors">
                    <table>
                        <tr>
                            <td style="font-size:1rem; font-weight:bold;">Aprt ID:</td>
                            <td style="padding:5px;"><?= $request['aprt_id'] ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:1rem; font-weight:bold;">Name:</td>
                            <td style="padding:5px;"><?= $request['cus_name'] ?></td>
                        </tr>
                    </table>
                    <div class="form-container">
                        <?= htmlspecialchars($request['message']) ?>
                    </div>
                    <div class="form-container">
                    <?php
    $images = getMaintenanceImages($request['main_id']);
    if (!empty($images)) {
        foreach ($images as $imagePath): ?>
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Maintenance image" style="max-width:100px; margin-right:5px;">
        <?php endforeach;
    } else {
        echo "No images available.";
    }
    ?>

                    </div>
                    <div class="status-select">
                        <select name="status" data-id="<?= $request['main_id'] ?>" class="status-dropdown" id="status1">
                            <option value="pending" <?= $request['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="working_on" <?= $request['status'] == 'working_on' ? 'selected' : '' ?>>Working On</option>
                            <option value="assigned" <?= $request['status'] == 'assigned' ? 'selected' : '' ?>>Assigned</option>
                            <option value="completed" <?= $request['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="../script.js"></script>
<script>
document.querySelectorAll('.status-dropdown').forEach(dropdown => {
    dropdown.addEventListener('change', function() {
        const requestId = this.dataset.id;
        const newStatus = this.value;

        // AJAX request to update status
        fetch('update_mstatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: requestId, status: newStatus })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: 'The status has been updated successfully!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                  
                 
              } else {
                  alert('Failed to update status');
              }
          });
    });
});
</script>
</body>
</html>
