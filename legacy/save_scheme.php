<?php
include  "db/dblink.php";

$q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
$found = mysqli_num_rows($q);
if (!$found) {
    header("location:signout.php");
}

$user = mysqli_fetch_assoc($q);

$scheme_name = mysqli_real_escape_string($conn, $_GET['scheme_name']);
$account_type = mysqli_real_escape_string($conn, $_GET['account_type']);

$q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE scheme_name='".$scheme_name."' AND account_type='".$account_type."' AND user_id='Administrator')");
$found = mysqli_num_rows($q);

if (!$found) {
    $q = mysqli_query($conn, "INSERT INTO pricing_schemes(scheme_name,account_type,user_id) VALUES('" . $scheme_name . "','" . $account_type . "','Administrator')");
    echo "Saved";
}else{
    echo "Duplicate";
}
?>