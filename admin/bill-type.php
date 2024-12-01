<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
   <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2>New Bill</h2>

            <section class="dashboard" style="grid-template-columns: repeat(3, minmax(200px, 1fr)); margin-bottom:5vw;">
                <a href="new-electricity.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Electricity</div></a>
                <a href="new-water.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Water</div></a>
                <a href="new-telephone.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Internet and Phone</div></a>
                <a href="new-gas.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Gas</div></a>
                <a href="new-gym.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Gym Membership</div></a>
                <a href="new-other.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Other</div></a>
            </section>

            <h2>History</h2>

            <section class="dashboard" style="grid-template-columns: repeat(3, minmax(200px, 1fr));">
                <a href="hist-electricity.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Electricity</div></a>
                <a href="hist-water.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Water</div></a>
                <a href="hist-telephone.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Internet and Phone</div></a>
                <a href="hist-gas.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Gas</div></a>
                <a href="hist-gym.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Gym Membership</div></a>
                <a href="hist-other.php" style="text-decoration:none; color: black; font-size:1.5rem; font-weight:bold;"><div class="card">Other</div></a>
            </section>
            
        </div>
    </div>

    <script src="../script.js"></script>
</body>
</html>
