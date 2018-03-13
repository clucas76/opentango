<?php

include_once('comm.inc.php');

$t=$_POST['ip'];
echo $t;

send_to_tangod("bwping:" . $t . "");

?>
