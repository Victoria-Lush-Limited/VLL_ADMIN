<?php
include "db/dblink.php";
include "smpp/smpp_config.php";

require_once 'phpsmpp/smppclient.class.php';
require_once 'phpsmpp/gsmencoder.class.php';
require_once 'phpsmpp/sockettransport.class.php';

// Construct transport and client
$transport = new SocketTransport(array($smpp_server), $smpp_port);
$transport->setRecvTimeout(1000);
$transport->setSendTimeout(40000);
$smpp = new SmppClient($transport);

// Activate binary hex-output of server interaction
$smpp->debug = true;
$transport->debug = true;

// Open the connection
$transport->open();
$smpp->bindReceiver($smpp_user, $smpp_password);


$sender_id="MAZUBU";

$source = new SmppAddress($sender_id, SMPP::TON_ALPHANUMERIC);
$report = $smpp->queryStatus('8263841782970440595', $source);

$smpp->close();

print_r($report);
