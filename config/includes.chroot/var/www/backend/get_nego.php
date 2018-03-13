<?php

if(!class_exists('SQLite3'))
  die("SQLite 3 NOT supported.");

$base = new SQLite3("opentango.db");

$query = "SELECT value FROM configuration WHERE type = 'type_ip';";
$results = $base->query($query);
$row = $results->fetchArray();
$type_ip = $row[0];


$auto 	= "";
$speed 	= "";
$duplex = "";
$link 	= "";

$output = array();

// Nego
$cmd = "/sbin/ethtool eth0 2> /dev/null | grep Auto | tr -s \"\\t\" \" \" | sed \"s/ //g\" | cut -d \":\" -f 2";
exec($cmd, $output);
$auto = $output;

unset($output);

// Speed
// $cmd = "/sbin/ethtool eth0";
$cmd = "/sbin/ethtool eth0 2> /dev/null | grep \"Speed\" | tr -s \"\\t\" \" \" | sed \"s/ //g\" | cut -d \":\" -f 2";
exec($cmd, $output);
$speed = $output;

unset($output);

// Duplex
$cmd = "/sbin/ethtool eth0 2> /dev/null | grep \"Duplex\" | tr -s \"\\t\" \" \" | sed \"s/ //g\" | cut -d \":\" -f 2";
exec($cmd, $output);
$duplex = $output;

unset($output);

// UP/DOWN
// $cmd = "/sbin/ethtool eth0|grep \"detected\"|tr -s \"\\t\" \" \"|sed \"s/ //g\"|cut -d \":\" -f 2 ";
$cmd = "cat /sys/class/net/eth0/carrier";
exec($cmd, $output);
$link = $output;

echo "" . $speed[0] . ":" . $duplex[0] . ":" . $auto[0] . ":" . $link[0] . ":" . $type_ip ;
?>
