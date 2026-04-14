<?php
include "db/dblink.php";

$q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
$found = mysqli_num_rows($q);
if (!$found) {
    header("location:signout.php");
}

$user = mysqli_fetch_assoc($q);

$scheme_id = mysqli_real_escape_string($conn, $_GET['scheme_id']);
$q = mysqli_query($conn, "DELETE FROM pricing_schemes WHERE scheme_id='" . $scheme_id . "' AND user_id='Administrator'");
