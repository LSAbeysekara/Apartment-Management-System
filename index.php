<?php require('./config.php') ?>
<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$errors = array();
?>
<?php

if (isset($_POST['login'])) {
    
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    $max_login_attempts = 5;
    if ($_SESSION['login_attempts'] >= $max_login_attempts) {
        die("Too many failed login attempts. Please try again later.");
    }

    if (!isset($_POST['username']) || strlen(trim($_POST['username'])) < 1) {
        $errors[] = 'Username is Missing or Invalid';
    }

    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
        $errors[] = 'Password is Missing or Invalid';
    }

    if (empty($errors)) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $hash_psw = sha1($password);

       
        if (strpos($username, 'CUS0') === 0) {
           
            $query = mysqli_query($conn, "SELECT * FROM tbl_customer WHERE cus_username = '$username' AND cus_password = '$hash_psw' LIMIT 1");
            $num_rows = mysqli_num_rows($query);
            $row = mysqli_fetch_array($query);

            if ($num_rows > 0) {
                
                $_SESSION['cus_id'] = $row['cus_id'];
                $_SESSION['cus_name'] = $row['cus_name']; 
                $_SESSION['login-ok'] = 'Success';

                // Notification showing session
                $sql00 = "SELECT message FROM tbl_notify 
                        WHERE status = 'active' 
                        AND CURDATE() BETWEEN start_date AND end_date";

                $res00 = mysqli_query($conn, $sql00);
                $notifications = [];

                if ($res00 && mysqli_num_rows($res00) > 0) {
                    $_SESSION['notification'] = 'OK';
                }

                
                header('Location: ./owner/index.php');
                exit;
            } else {
                $errors[] = 'Invalid customer username or password';
                $_SESSION['login_attempts']++;
            }
        } else {
            
            $query = mysqli_query($conn, "SELECT * FROM tbl_employee WHERE emp_username = '$username' AND emp_password = '$hash_psw' LIMIT 1");
            $num_rows = mysqli_num_rows($query);
            $row = mysqli_fetch_array($query);

            if ($num_rows > 0) {
                
                $_SESSION['staffname'] = $row['emp_name'];
                $_SESSION['user_type'] = $row['emp_type'];
                $_SESSION['staffid'] = $row['emp_id'];
                $_SESSION['login-ok'] = 'Success';


                header('Location: ./admin/index.php');
               
            } else {
                $errors[] = 'Invalid employee username or password';
                $_SESSION['login_attempts']++;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login-styles.css">
</head>

<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="#" method="post">
                <div class="input-box">
                    <input type="text" name="username" id="username" required>
                    <label>User Name</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password" required>
                    <label>Password</label>
                    <span class="show-password" onclick="togglePassword()">Show</span>
                </div>
                <?php
                if (isset($errors) && !empty($errors)) {
                    echo '<center><p style="color:red" >Invalid username or Password </p></center><br>';
                }
                ?>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="submit" name="login" class="login-btn" value="Login">
            </form>
        </div>

        <div class="images-section">
            <div class="image-wrapper">
                <img src="./assets/images/background/3.jpg" alt="Image 1" class="animated-image">
                <div class="image-description">Modern and Beautiful bedrooms and Dining Room.</div>
            </div>
            <div class="image-wrapper">
                <img src="./assets/images/background/5.jpeg" alt="Image 2" class="animated-image">
                <div class="image-description">Next Level Gym Facilities.</div>
            </div>
            <div class="image-wrapper">
                <img src="./assets/images/background/6.jpg" alt="Image 3" class="animated-image">
                <div class="image-description">Great Space comes with Great Security for your vehicle.</div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
