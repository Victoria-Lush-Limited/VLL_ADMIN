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

$new_password = mysqli_real_escape_string($conn, $_POST['agent_new_password']);
$agent_id = mysqli_real_escape_string($conn, $_GET['agent_id']);

$q = mysqli_query($conn, "SELECT * FROM agents WHERE user_id='" . $agent_id . "'");
$found = mysqli_num_rows($q);
if ($found) {
    if (strlen($new_password) >= 6) {
        $new_password = md5($new_password);
        $u = mysqli_query($conn, "UPDATE agents SET password='" . $new_password . "' WHERE user_id='" . $agent_id . "'");
        header("location:agent_account.php?agent_id=" . $agent_id . "&r=Password changed");
    } else {
        header("location:agent_account.php?agent_id=" . $agent_id . "&r=Invalid password");
    }
} else {
    header("location:agent_account.php?agent_id=" . $agent_id . "&r=Incorrect password");
}
