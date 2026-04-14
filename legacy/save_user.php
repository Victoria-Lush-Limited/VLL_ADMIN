<?php

include "db/dblink.php";

$user_id = mysqli_real_escape_string($conn, $_POST['mobile_number']);
$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
$password = md5(mysqli_real_escape_string($conn, $_POST['password']));

$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $user_id . "' AND password='" . $password . "'");
$found = mysqli_num_rows($q);

if ($found) {
    header("location:register.php?r=Mobile Number already registered");
} else {
    $status = "Pending";
    $vcode = mt_rand(100000, 999999);
    $rcode = "";
    $i = mysqli_query($conn, "INSERT INTO users(user_id,password,client_name,status,vcode,rcode) VALUES('" . $user_id . "','" . $password . "','" . $client_name . "','" . $status . "','" . $vcode . "','" . $rcode . "')");
    if ($i) {
        $_SESSION['user_id'] = $user_id;
        $sms_date = time();
        $sms_status = "Pending";
        $sender_id = $app['sender_id'];
        $recipient = $_SESSION['user_id'];
        $message = "Your verification code is: " . $vcode;

        $q = mysqli_query($conn, "INSERT INTO sms(sms_date,user_id,sender_id,recipient,sms_status,message) VALUES('" . $sms_date . "','" . $user_id . "','" . $sender_id . "','" . $recipient . "','" . $sms_status . "','" . $message . "')");

        header("location:verification.php");
    } else {
        header("location:register.php?r=Something went wrong, please try again later");
    }
}
