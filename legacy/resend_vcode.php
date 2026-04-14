<?php

include "db/dblink.php";

$user_id = $_SESSION['user_id'];

$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);

if ($found) {
    $user = mysqli_fetch_assoc($q);
    $vcode = $user['vcode'];
    $sms_date = time();
    $sms_status = "Pending";
    $sender_id = $app['sender_id'];
    $recipient = $_SESSION['user_id'];
    $message = "Your verification code is: " . $vcode;
    $q = mysqli_query($conn, "INSERT INTO sms(sms_date,user_id,sender_id,recipient,sms_status,message) VALUES('" . $sms_date . "','" . $user_id . "','" . $sender_id . "','" . $recipient . "','" . $sms_status . "','" . $message . "')");
    header("location:verification.php");
} else {
    header("location:signout.php");
}
