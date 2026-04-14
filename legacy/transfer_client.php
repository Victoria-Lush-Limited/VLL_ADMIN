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

$client_id = mysqli_real_escape_string($conn, $_GET['client_id']);
$reseller_id = mysqli_real_escape_string($conn, $_POST['transfer_reseller_id']);

$u = mysqli_query($conn, "UPDATE users SET reseller_id='" . $reseller_id . "' WHERE user_id='".$client_id."'");
header("location:client_account.php?client_id=" . $client_id);