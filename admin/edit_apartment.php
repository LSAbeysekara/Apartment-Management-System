
<?php require('../config.php'); 
$page_title="Edit Appartment";?>
<?php if (!isset($_SESSION['staffname'])) {
  echo "<script> window.location.replace('../index.php'); </script>";
} else { 
    if (!isset($_GET['id'])) {
        echo "<script> window.location.replace('apartment.php'); </script>";
        exit;
    }

    $id = $_GET['id'];
    $query = "SELECT aprt_id, block_no, floor_no, aprt_no, room_count, bathroom_count, area, status FROM tbl_apartment WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $apartment = $result->fetch_assoc();
    if (!$apartment) {
        echo "<script> window.location.replace('apartment.php'); </script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Apartment</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Edit Apartment Details</h2>
        <form id="editApartmentForm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="aprt_id">Apartment ID</label>
                <input type="text" id="aprt_id" name="aprt_id" value="<?php echo $apartment['aprt_id']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="block">Block Number</label>
                <input type="number" id="block" name="block" value="<?php echo $apartment['block_no']; ?>">
            </div>
            <div class="form-group">
                <label for="floor">Floor Number</label>
                <input type="number" id="floor" name="floor" value="<?php echo $apartment['floor_no']; ?>">
            </div>
            <div class="form-group">
                <label for="unit">Unit Number</label>
                <input type="number" id="unit" name="unit" value="<?php echo $apartment['aprt_no']; ?>">
            </div>
            <div class="form-group">
                <label for="room">Room Count</label>
                <input type="number" id="room" name="room" min="0" value="<?php echo $apartment['room_count']; ?>">
            </div>
            <div class="form-group">
                <label for="bathroom">Bathroom Count</label>
                <input type="number" id="bathroom" name="bathroom" min="0" value="<?php echo $apartment['bathroom_count']; ?>">
            </div>
            <div class="form-group">
                <label for="area">Area (sqft)</label>
                <input type="number" id="area" name="area" min="0" value="<?php echo $apartment['area']; ?>">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Open" <?php echo ($apartment['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
                    <option value="Occupied" <?php echo ($apartment['status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                </select>
            </div>
            <button type="button" class="submit-btn" onclick="updateApartment()">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateApartment() {
            $.ajax({
                url: 'update_apartment.php',
                type: 'POST',
                data: $('#editApartmentForm').serialize(),
                success: function(response) {
                    let data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Apartment details have been updated successfully.',
                        }).then(() => {
                            window.location.replace('apartment.php');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to update apartment details.',
                        });
                    }
                }
            });
        }
    </script>
</body>
</html>
<?php } ?>
