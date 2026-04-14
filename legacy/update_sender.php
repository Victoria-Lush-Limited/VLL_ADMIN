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

$id = mysqli_real_escape_string($conn, $_GET['id']);
$id_type = mysqli_real_escape_string($conn, $_POST['id_type']);
$id_status = mysqli_real_escape_string($conn, $_POST['id_status']);

$q = mysqli_query($conn, "SELECT * FROM senders WHERE id='" . $id . "'");
$found = mysqli_num_rows($q);

if (!$found) {
    header("location:senders.php?r=Sender ID not found");
} else {
    $u = mysqli_query($conn, "UPDATE senders SET id_type='" . $id_type . "',id_status='" . $id_status . "' WHERE id='" . $id . "'");
    header("location:senders.php");
}
