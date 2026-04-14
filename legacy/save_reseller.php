<?php

include "db/dblink.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("location:signout.php");
} else {
    $q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
    $found = mysqli_num_rows($q);
    if ($found) {
        $user = mysqli_fetch_assoc($q);
    } else {
        header("location:signout.php");
    }
}

$user_id = mysqli_real_escape_string($conn, $_POST['email']);
$phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
$business_address = mysqli_real_escape_string($conn, $_POST['business_address']);
$password = md5(mysqli_real_escape_string($conn, $_POST['new_password']));
$scheme_id= mysqli_real_escape_string($conn, $_POST['scheme_id']);
$sender_id= mysqli_real_escape_string($conn, $_POST['sender_id']);
$date_created= time();

$q = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);

if ($found) {
    header("location:resellers.php?r=Email address already registered");
} else {
    $status = "Active";
    
    $vcode = "";
    $rcode = "";
    
    $i = mysqli_query($conn, "INSERT INTO resellers(user_id,password,business_name,business_address,phone_number,email,status,vcode,rcode,scheme_id,sender_id,date_created) VALUES('" . $user_id . "','" . $password . "','" . $business_name . "','".$business_address."','".$phone_number."','".$email."','" . $status . "','".$vcode."','" . $rcode . "','".$scheme_id."','".$sender_id."','".$date_created."')");
    if ($i) {
        header("location:reseller_account.php?reseller_id=".$user_id);
    } else {
      header("location:resellers.php?r=failed");
    }
}