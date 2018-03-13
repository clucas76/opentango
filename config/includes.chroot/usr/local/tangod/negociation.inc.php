<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

function set_negociation($vitesse, $duplex, $dev, $auto)
{
	logging_debug("SET_NEGOCIATION");
	
	$nego_l = $vitesse . "-" . $duplex;

	// 100baseTx-FD, 100baseTx-HD, 10baseT-FD, or 10baseT-HD operation.

	$cmd = "";

	if ($auto == 1) {
		$cmd = "ethtool -s " . $dev . " autoneg on";
	} else {
		$cmd = "ethtool -s " . $dev . " speed " . $vitesse . " duplex " . $duplex . " autoneg off";
	}

	logging_debug($cmd . "\n");

	exec($cmd);
	
}
?>
