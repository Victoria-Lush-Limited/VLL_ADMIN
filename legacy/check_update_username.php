<?php

include "db/dblink.php";

$username = mysqli_real_escape_string($conn, $_GET['username']);
$client_id = mysqli_real_escape_string($conn, $_GET['client_id']);

$q = mysqli_query($conn, "SELECT * FROM users WHERE username='".$username."' AND user_id != '".$client_id."'");
$found=mysqli_num_rows($q);
if($found){
    echo "Taken";
}else{
    echo "Available";
}