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

$user_id = mysqli_real_escape_string($conn, $_GET['client_id']);
$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
$scheme_id = mysqli_real_escape_string($conn, $_POST['scheme_id']);
$username= mysqli_real_escape_string($conn, $_POST['username']);
$email= mysqli_real_escape_string($conn, $_POST['email']);
$contact_phone= mysqli_real_escape_string($conn, $_POST['contact_phone']);
$status= mysqli_real_escape_string($conn, $_POST['status']);
$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);

if (!$found) {
    header("location:clients.php?r=Client not found");
} else {
    $u = mysqli_query($conn, "UPDATE users SET client_name='" . $client_name . "',email='".$email."',contact_phone='".$contact_phone."',username='".$username."',scheme_id='" . $scheme_id . "',status='".$status."' WHERE user_id='" . $user_id . "' AND reseller_id='Administrator'");
    header("location:client_account.php?client_id=" . $user_id);
}
