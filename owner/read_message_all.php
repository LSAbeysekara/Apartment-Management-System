<?php include('../config.php'); ?>

<?php 
  if(isset($_SESSION['cus_id'])) {
    $cus_id = $_SESSION['cus_id'];
  }else{
    header('Location:../index.php');
    exit();
  }
?>

<?php
$read = "read";

// Corrected SQL syntax
$sql1 = "UPDATE tbl_bill SET read_m = '$read' WHERE cus_id = '$cus_id'";
$res1 = mysqli_query($conn, $sql1);

if($res1 == true){
    header("Location: bill.php");
} else {
    header("Location: index.php");
}
?>
