<?php

include_once('comm.inc.php');

$action	= $_POST['action'];
$id	= $_POST['vlan_id'];

$t = $action . ":" . $id . ":eth0"; 

echo $t;

send_to_tangod("vlan:" . $t . "");

?>
