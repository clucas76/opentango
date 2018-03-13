<?php

include_once('comm.inc.php');

$ip 	= $_GET['ip'];


send_to_tangod("ping:" . $ip );
$ret = read_from_tangod();

echo $ret;

?>
