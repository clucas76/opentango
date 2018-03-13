<?php

include_once('comm.inc.php');

$action		= $_POST['action'];
$route 		= $_POST['route'];
$cidr		= $_POST['cidr'];
$gateway	= $_POST['gateway'];

// add:172.16.0.0:16:192.168.1.254
// del:172.18.0.0:24:192.168.1.254 

$t = $action . ":" . $route . ":" . $cidr . ":" . $gateway;

send_to_tangod("route:" . $t . "");

?>
