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

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
$receipt_number = mysqli_real_escape_string($conn, $_GET['receipt_number']);
$payment_method = mysqli_real_escape_string($conn, $_GET['payment_method']);



$q = mysqli_query($conn, "SELECT * FROM sms_orders WHERE order_id='" . $order_id . "' AND order_status='Pending'");
$found = mysqli_num_rows($q);
if ($found) {
    $order = mysqli_fetch_assoc($q);
    $b = mysqli_query($conn, "SELECT (SUM(allocated)-SUM(consumed)) AS balance FROM transactions WHERE user_id='" . $_SESSION['user_id'] . "'");
    $bal = mysqli_fetch_assoc($b);
    $sms_balance = $bal['balance'];
    $tdate = time();
    $q = mysqli_query($conn, "UPDATE sms_orders SET receipt='" . $receipt_number . "',payment_method='" . $payment_method . "' WHERE order_id='" . $order_id . "'");
    $q = mysqli_query($conn, "INSERT INTO transactions(user_id,allocated,consumed,tdate) VALUES ('" . $order['user_id'] . "','" . $order['quantity'] . "','0','" . $tdate . "')");
    $q = mysqli_query($conn, "UPDATE sms_orders SET order_status='Allocated' WHERE order_id='" . $order['order_id'] . "'");
}