<?php
include  "db/dblink.php";

$q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
$found = mysqli_num_rows($q);
if (!$found) {
    header("location:signout.php");
}

$user = mysqli_fetch_assoc($q);

$scheme_id = mysqli_real_escape_string($conn, $_GET['scheme_id']);
$min_sms = mysqli_real_escape_string($conn, $_GET['min_sms']);
$max_sms = mysqli_real_escape_string($conn, $_GET['max_sms']);
$price = mysqli_real_escape_string($conn, $_GET['price']);

$q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE scheme_id='".$scheme_id."' AND user_id='Administrator')");
$found = mysqli_num_rows($q);

if (!$found) {
    $q = mysqli_query($conn, "INSERT INTO pricing(scheme_id,min_sms,max_sms,price) VALUES('" . $scheme_id . "','" . $min_sms . "','" . $max_sms . "','".$price."')");
}
?>