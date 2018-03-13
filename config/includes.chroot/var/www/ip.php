<?php

include_once('comm.inc.php');

$action		= $_POST['action'];
$ip		= $_POST['ip'];
$netmask	= $_POST['netmask'];
$intf		= $_POST['intf'];

$t = $action . ":" . $ip . ":" . $netmask . ":" . $intf;

send_to_tangod("ip:" . $t . "");

?>
