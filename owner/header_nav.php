<?php 
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <nav>
        <ul>
            <li>
                <a href="index.php">
                    <span class="circle <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" >
                        <span class="material-symbols-outlined icon">home</span>
                    </span>
                    <label class="nav-label">Home</label>
                </a>
            </li>
            <li>
                <a href="request-form.php">
                    <span class="circle <?php echo $current_page == 'request-form.php' ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined icon">description</span>
                    </span>
                    <label class="nav-label">Request Form</label>
                </a>
            </li>
            <li>
                <a href="maintenance.php">
                    <span class="circle <?php echo $current_page == 'maintenance.php' ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined icon">engineering</span>
                    </span>
                    <label class="nav-label">Maintenance</label>
                </a>
            </li>
            <li>
                <a href="complaint.php">
                    <span class="circle <?php echo $current_page == 'complaint.php' ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined icon">person_alert</span>
                    </span>
                    <label class="nav-label">Complain</label>
                </a>
            </li>
            <li>
                <a href="bill.php">
                    <span class="circle <?php echo $current_page == 'bill.php' ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined icon">payments</span>
                    </span>
                    <label class="nav-label">Bill & Payments</label>
                </a>
            </li>
            <li>
                <a href="profile.php">
                    <span class="circle <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                        <?php
                        // Define the profile directory and file extensions to check
                        $profile_dir = '../assets/images/profile_pic/';
                        $extensions = ['jpg', 'jpeg', 'png', 'gif']; // Supported image extensions
                        $image_path = null;

                        // Check if any profile image with the customer's ID exists
                        foreach ($extensions as $ext) {
                            if (file_exists($profile_dir . $cus_id . '.' . $ext)) {
                                $image_path = $profile_dir . $cus_id . '.' . $ext;
                                break;
                            }
                        }
                        ?>

                        <!-- Conditionally display profile image or default icon -->
                        <?php if ($image_path): ?>
                            <img src="<?php echo $image_path; ?>" alt="Profile Picture" style="width: 100%; height: 100%; border-radius: 50%;">
                        <?php else: ?>
                            <span class="material-symbols-outlined icon">person</span>
                        <?php endif; ?>
                    </span>
                    <label class="nav-label"><?php echo $cus_name; ?></label>
                </a>
            </li>
        </ul>
    </nav>
</header>
