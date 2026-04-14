<?php
include "db/dblink.php";

//header("Content-Type: application/json; charset=UTF-8");

$now = time();
$q = mysqli_query($conn, "SELECT * FROM outgoing WHERE sms_status='Pending' AND date_created <= '" . $now . "' AND attempts<1 LIMIT 500");
$found = mysqli_num_rows($q);

$json = array();

while ($outgoing = mysqli_fetch_assoc($q)) {

    array_push($json, $outgoing);
}

$postData = $json;

$Url = 'http://3.141.28.85/smpp_server.php';

$ch = curl_init($Url);
error_reporting(E_ALL);
ini_set('display_errors', 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($postData)
));

$response = curl_exec($ch);
if (!empty($response)) {

    $json_data = json_decode($response);
    foreach ($json_data as $data) {
        $u = mysqli_query($conn, "UPDATE outgoing SET attempts='" . $data->attempts . "',sms_status='" . $data->sms_status . "',smsc_id='" . $data->smsc_id . "' WHERE sms_id='" . $data->sms_id . "'");
    }
}
