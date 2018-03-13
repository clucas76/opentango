<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

function set_ip_auto($value)
{
	logging_debug("SET_IP_AUTO");
	
	$cmd = "";

	if ($value == "auto") {
		$cmd = "/sbin/dhclient eth0";
	} else {
		$cmd = "killall -KILL dhclient";
	}

	logging_debug($cmd . "\n");

	exec($cmd);
}
?>
