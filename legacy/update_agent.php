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

$user_id = mysqli_real_escape_string($conn, $_GET['agent_id']);
$agent_name = mysqli_real_escape_string($conn, $_POST['agent_name']);
$region = mysqli_real_escape_string($conn, $_POST['region']);
$agent_address = mysqli_real_escape_string($conn, $_POST['agent_address']);
$scheme_id = mysqli_real_escape_string($conn, $_POST['scheme_id']);
$sender_id = $_POST['sender_id'];
$phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

$q = mysqli_query($conn, "SELECT * FROM agents WHERE user_id='" . $user_id . "'");
$found = mysqli_num_rows($q);

if (!$found) {
    header("location:agents.php?r=Agent not found");
} else {
    $u = mysqli_query($conn, "UPDATE agents SET agent_name='" . $agent_name . "',region='".$region."',agent_address='".$agent_address."' ,scheme_id='" . $scheme_id . "',phone_number='".$phone_number."',email='".$email."' WHERE user_id='" . $user_id . "'");
    header("location:agent_account.php?agent_id=" . $user_id);
}
