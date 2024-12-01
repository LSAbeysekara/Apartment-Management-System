<?php 
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h1><?php if(!empty($page_title)){echo $page_title;} else { echo ''; } ?></h1>
    <ul class="nav-links">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span>User Registration</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="user-reg.php" class="<?php echo $current_page == 'user-reg.php' ? 'active' : ''; ?>">New Registration Form</a></li>
                <li><a href="users.php" class="<?php echo $current_page == 'users.php' ? 'active' : ''; ?>">Users</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span>Apartment Registration</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="aprt-reg.php" class="<?php echo $current_page == 'aprt-reg.php' ? 'active' : ''; ?>">New Registration Form</a></li>
                <li><a href="apartment.php" class="<?php echo $current_page == 'apartment.php' ? 'active' : ''; ?>">Apartments</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span>Entry Pass</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="new-entry-pass.php" class="<?php echo $current_page == 'new-entry-pass.php' ? 'active' : ''; ?>">New Entry Pass Form</a></li>
                <li><a href="entry-pass-history.php" class="<?php echo $current_page == 'entry-pass-history.php' ? 'active' : ''; ?>">Entry Pass History</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span>Fill Out Form</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="new-fill-out.php" class="<?php echo $current_page == 'new-fill-out.php' ? 'active' : ''; ?>">New Fill Out Form</a></li>
                <li><a href="fill-out-history.php" class="<?php echo $current_page == 'fill-out-history.php' ? 'active' : ''; ?>">Fill Out Form History</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span>Maid Pass</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="new-maid-pass.php" class="<?php echo $current_page == 'new-maid-pass.php' ? 'active' : ''; ?>">New Maid Pass Form</a></li>
                <li><a href="maid-pass-history.php" class="<?php echo $current_page == 'maid-pass-history.php' ? 'active' : ''; ?>">Maid Pass History</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span>Vehicle Pass</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="new-vehicle-pass.php" class="<?php echo $current_page == 'new-vehicle-pass.php' ? 'active' : ''; ?>">New Vehicle Pass Form</a></li>
                <li><a href="vehicle-pass-history.php" class="<?php echo $current_page == 'vehicle-pass-history.php' ? 'active' : ''; ?>">Vehicle Pass History</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="bill-type.php" class="nav-link <?php echo in_array($current_page, ['new-water.php', 'new-other.php', 'new-gas.php', 'new-gym.php', 'new-electricity.php', 'hist-water.php', 'hist-other.php', 'hist-gas.php', 'hist-gym.php', 'hist-electricity.php','bill-type.php']) ? 'active' : ''; ?>">
                <span>Payments and Bills</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="maintenance.php" class="nav-link <?php echo $current_page == 'maintenance.php' ? 'active' : ''; ?>">
                <span>Maintenance</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="complaint.php" class="nav-link <?php echo $current_page == 'complaint.php' ? 'active' : ''; ?>">
                <span>Complaint</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link <?php echo in_array($current_page, ['profile.php', 'change-password.php']) ? 'active' : ''; ?>">
                <span>User Profile</span>
                <span class="arrow">▶</span>
            </a>
            <ul class="sub-links">
                <li><a href="profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">Personal Information</a></li>
                <li><a href="change-password.php" class="<?php echo $current_page == 'change-password.php' ? 'active' : ''; ?>">Change Password</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="nav-link <?php echo $current_page == 'logout.php' ? 'active' : ''; ?>">
                <span>Log Out</span>
            </a>
        </li>
    </ul>
</div>
