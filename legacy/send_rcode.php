<?php

include "db/dblink.php";

$user_id = mysqli_real_escape_string($conn, $_POST['mobile_number']);
$rcode = mt_rand(100000, 999999);

$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);

if ($found) {
    $u = mysqli_query($conn, "UPDATE users SET rcode='" . $rcode . "' WHERE user_id='" . $user_id . "'");
    $q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $user_id . "'");
    $user = mysqli_fetch_assoc($q);
    $_SESSION['temp_user_id']=$user['user_id'];
    $rcode = $user['rcode'];
    $sms_date = time();
    $sms_status = "Pending";
    $sender_id = $app['sender_id'];
    $recipient = $user['user_id'];
    $message = "Your password reset code is: " . $rcode;
    $q = mysqli_query($conn, "INSERT INTO sms(sms_date,user_id,sender_id,recipient,sms_status,message) VALUES('" . $sms_date . "','" . $user_id . "','" . $sender_id . "','" . $recipient . "','" . $sms_status . "','" . $message . "')");

    header("location:recover.php");
} else {
    header("location:forgot.php?User account not found");
}
