<?php

include_once('comm.inc.php');

$ip_auto 	= $_POST['ip_auto'];

if(!class_exists('SQLite3'))
  die("SQLite 3 NOT supported.");

$base = new SQLite3("backend/opentango.db");

$query = "UPDATE configuration SET value='" . $ip_auto . "' WHERE type = 'type_ip';";
$results = $base->exec($query);


send_to_tangod("ip_auto:" . $ip_auto );
sleep(5);

?>
