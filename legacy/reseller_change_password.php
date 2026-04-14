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

$new_password = mysqli_real_escape_string($conn, $_POST['client_new_password']);
$reseller_id = mysqli_real_escape_string($conn, $_GET['reseller_id']);

$q = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $reseller_id . "'");
$found = mysqli_num_rows($q);
if ($found) {
    if (strlen($new_password) >= 6) {
        $new_password = md5($new_password);
        $u = mysqli_query($conn, "UPDATE resellers SET password='" . $new_password . "' WHERE user_id='" . $reseller_id . "'");
        header("location:reseller_account.php?reseller_id=" . $reseller_id . "&r=Password changed");
    } else {
        header("location:reseller_account.php?reseller_id=" . $reseller_id . "&r=Invalid password");
    }
} else {
    header("location:reseller_account.php?reseller_id=" . $reseller_id . "&r=User not found");
}
