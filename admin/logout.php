<?php
session_start();
unset($_SESSION['staffname']);
unset($_SESSION['user_type']);
unset($_SESSION['staffid']);
header("Location:../index.php");
?>