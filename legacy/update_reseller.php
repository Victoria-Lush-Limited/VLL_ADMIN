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

$user_id = mysqli_real_escape_string($conn, $_GET['reseller_id']);
$business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
$business_address = mysqli_real_escape_string($conn, $_POST['business_address']);
$scheme_id = mysqli_real_escape_string($conn, $_POST['scheme_id']);
$sender_id = $_POST['sender_id'];
$phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

$q = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);

if (!$found) {
    header("location:resellers.php?r=Reseller not found");
} else {
    $u = mysqli_query($conn, "UPDATE resellers SET business_name='" . $business_name . "',business_address='".$business_address."' ,scheme_id='" . $scheme_id . "',sender_id='".$sender_id."',phone_number='".$phone_number."',email='".$email."' WHERE user_id='" . $user_id . "'");
    header("location:reseller_account.php?reseller_id=" . $user_id);
}
