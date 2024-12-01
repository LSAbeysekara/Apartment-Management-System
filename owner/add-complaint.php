<?php include('../config.php'); ?>

<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize input
    $name = $conn->real_escape_string(trim($_POST['name']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $block = $conn->real_escape_string(trim($_POST['block']));
    $floor = $conn->real_escape_string(trim($_POST['floor']));
    $apartment = $conn->real_escape_string(trim($_POST['apartment']));
    $message = $conn->real_escape_string(trim($_POST['complaint']));
    $cus_id = $conn->real_escape_string(trim($_POST['cus_id']));
    $status = 'Pending';


    $apartment_id = 'APRT122';
    // Get apartment ID from customer table
    $sql = "SELECT * FROM tbl_customer WHERE cus_id='$cus_id'";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);

    if($count>0){
        while($row=mysqli_fetch_assoc($res)){
            $apartment_id = $row['apartment_id'];
        }
    }

    // Get current Date and time
    // Set the timezone to Sri Lanka
    date_default_timezone_set('Asia/Colombo');

    // Get current date and time in Sri Lanka timezone
    $currentDateTime = date("Y-m-d H:i:s");


    // Validate required fields
    if ($name && $phone && $block && $floor && $apartment && $message) {

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO tbl_complaint (cus_id, aprt_id, req_date, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $cus_id, $apartment_id, $currentDateTime, $message);

        if ($stmt->execute()) {
            //Create the com_id
            // Fetch the last ID from the database
            $last_id = $conn->query("SELECT MAX(com_id) AS last_id FROM tbl_complaint")->fetch_assoc()['last_id'];

            // Convert last_id to an integer, handle the case when last_id is null (i.e., no records in the table)
            $last_id = $last_id ? (int)substr($last_id, 4) : 0; // Extract numeric part and convert to int

            // Generate the new com_id
            $com_id = 'COM' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

            // Update the com_id in the complaint table
            $sql1 = "UPDATE tbl_complaint SET com_id = '$com_id' ORDER BY id DESC LIMIT 1";
            $res1 = mysqli_query($conn, $sql1);

            if($res1 == true){

                if (!empty($_FILES['images']['name'][0])) {

                    // Create folder to upload the image files
                    $directoryPath = "../assets/images/complaint/$com_id";

                    // Check if the directory exists, if not, create it
                    if (!is_dir($directoryPath)) {
                        mkdir($directoryPath, 0777, true); // Creates directory with permissions
                    }

                    // Image upload process
                    $imagePaths = [];
                    $uploadSuccess = true;

                    foreach ($_FILES['images']['name'] as $key => $fileName) {
                        $fileTmpPath = $_FILES['images']['tmp_name'][$key];
                        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                        
                        // Generate a unique filename to avoid overwriting
                        $uniqueFileName = uniqid() . '.' . $fileType;
                        $targetFilePath = $directoryPath . '/' . $uniqueFileName; // Correct the path concatenation

                        // Validate file type (only images)
                        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                        if (in_array(strtolower($fileType), $allowedTypes)) {
                            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                                $imagePaths[] = $targetFilePath; // Save the file path
                                $_SESSION['form_success'] = 'Complaint Successful';
                                
                            } else {
                                $_SESSION['form_error'] = 'Failed to upload files';
                            }
                        } else {
                            $_SESSION['form_error'] = 'Incorrect file type';
                        }
                    }
                    
                } else {
                    $_SESSION['form_success'] = 'Complaint Successful';
                }
            }else{
                $_SESSION['form_error'] = 'Something went wrong! Try again';
            }

        } else {
            $_SESSION['form_error'] = 'Something went wrong! Try again';
        }

    } else {
        $_SESSION['form_error'] = 'Something went wrong! Try again';
    }
}

$conn->close();
$stmt->close();

// Redirect back with success or error message
header("Location: complaint.php");
exit();
?>
