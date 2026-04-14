<?php
include "db/dblink.php";

$q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
$found = mysqli_num_rows($q);
if (!$found) {
    header("location:signout.php");
}

$user = mysqli_fetch_assoc($q);


$agent_id = mysqli_real_escape_string($conn, $_GET['agent_id']);
$q = mysqli_query($conn, "DELETE FROM agents WHERE user_id='" . $agent_id . "'");
header("location:agents.php");