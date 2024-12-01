<?php  include('../config/constant.php'); 

if(isset($_SESSION['cus_id'])){
    $cus_id = $_SESSION['cus_id'];
}else{
    header('Location: ../index.php');
}


$sql = "SELECT * FROM student_enroll WHERE st_enr_id = '$st_enr_id'";

$res = mysqli_query($conn, $sql);

$count = mysqli_num_rows($res);

if($count>0){
    
    while($row=mysqli_fetch_assoc($res)){
        $st_id = $row['st_id'];
        $cl_id = $row['cl_id'];
    }

    $sql1 = "SELECT * FROM class WHERE cl_id = '$cl_id'";

    $res1 = mysqli_query($conn, $sql1);

    $count1 = mysqli_num_rows($res1);

    if($count1>0){
        
        while($row=mysqli_fetch_assoc($res1)){
            $cl_fee = $row['cl_fee'];
            $cl_title = $row['cl_title'];
            $cl_grade = $row['cl_grade'];
        }
    }
    
}



$amount = $cl_fee;
$merchant_id = "1223737";
$order_id = uniqid();
$merchant_secret = "MTM1MTQ1ODA0NDQyOTA5MDI0MDkxNDk2Mzc4NzQwMTI4OTM5Njc5Mg==";
$currency = "LKR";

$hash = strtoupper(
    md5(
        $merchant_id . 
        $order_id . 
        number_format($amount, 2, '.', '') . 
        $currency .  
        strtoupper(md5($merchant_secret)) 
    ) 
);

$array = [];

$array["name"] = "1223737";
$array["amount"] = "1223737";
$array["merchant_id"] = "1223737";
$array["order_id"] = "1223737";
$array["currency"] = "1223737";
$array["hash"] = $hash;
$array["st_enr_id"] = $st_enr_id;  

$jsonObj = json_encode($array);

echo $jsonObj;
?>
