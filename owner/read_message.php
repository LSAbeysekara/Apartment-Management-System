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
$bill_id = $_GET['bill_id'];
$read = "read";

// Corrected SQL syntax
$sql1 = "UPDATE tbl_bill SET read_m = '$read' WHERE bill_id = '$bill_id'";
$res1 = mysqli_query($conn, $sql1);

if($res1 == true){
    header("Location: bill.php");
} else {
    header("Location: index.php");
}
?>
