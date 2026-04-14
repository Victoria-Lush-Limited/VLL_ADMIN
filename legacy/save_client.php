<?php

include "db/dblink.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("location:signout.php");
} else {
    $q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
    $found = mysqli_num_rows($q);
    if ($found) {
        $user = mysqli_fetch_assoc($q);
        if ($user['status'] == "Pending") {
            header("location:verification.php");
        }
    } else {
        header("location:signout.php");
    }
}

$user_id = mysqli_real_escape_string($conn, $_POST['phone_number']);
$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
$password = md5(mysqli_real_escape_string($conn, $_POST['new_password']));
$scheme_id = mysqli_real_escape_string($conn, $_POST['scheme_id']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$contact_phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
$reseller_id = "Administrator";
$user_date_created = time();

$q = mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "'");
$found = mysqli_num_rows($q);

if ($found) {
    header("location:clients.php?r=Username already registered");
}

$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);


if ($found) {
    header("location:clients.php?r=Phone number already registered");
} else {
    $status = "Pending";
    $vcode = mt_rand(100000, 999999);
    $rcode = "";
    $i = mysqli_query($conn, "INSERT INTO users(user_id,password,client_name,status,vcode,rcode,scheme_id,username,email,contact_phone,reseller_id,user_date_created) VALUES('" . $user_id . "','" . $password . "','" . $client_name . "','" . $status . "','" . $vcode . "','" . $rcode . "','" . $scheme_id . "','" . $username . "','" . $email . "','" . $contact_phone . "','" . $reseller_id . "','" . $user_date_created . "')");
    if ($i) {
        $sms_status = "Pending";
        $sender_id = $app['sender_id'];
        $phone_number = $user_id;
        $message = "Your verification code is: " . $vcode;
        $credits = ceil(strlen($message / 160));
        $schedule = "None";
        $start_date = $user_date_created;
        $end_date = $start_date;
        $date_created = $user_date_created;
        $attempts = 0;
        $sms_status = "Pending";
        $user_id = $user['user_id'];
        $smsc_id = "";

        $q = mysqli_query($conn, "INSERT INTO outgoing(phone_number,sender_id,message,credits,schedule,start_date,end_date,date_created,attempts,sms_status,user_id,smsc_id) VALUES('" . $phone_number . "','" . $sender_id . "','" . $message . "','" . $credits . "','" . $schedule . "','" . $start_date . "','" . $end_date . "','" . $date_created . "','" . $attempts . "','" . $sms_status . "','" . $user_id . "','" . $smsc_id . "')");

        header("location:client_account.php?client_id=".$user_id);
    } else {
        header("location:clients.php?r=failed");
    }
}
