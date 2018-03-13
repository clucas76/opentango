<?php

include_once('comm.inc.php');

$nego 	= $_POST['nego'];
$auto	= $_POST['auto'];

$arr = explode("-", $nego);
$speed = $arr[0];
$duplex = $arr[1];

$t = $speed . ":" . $duplex . ":eth0:" . $auto;

send_to_tangod("nego:" . $t );

?>
