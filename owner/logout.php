<?php
session_start();
unset($_SESSION['cus_id']);
unset($_SESSION['cus_name']);

header("Location:../index.php");
?>
