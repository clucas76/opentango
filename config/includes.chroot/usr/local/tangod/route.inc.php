<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

function set_routing($action, $subnet, $length, $gateway, $dev)
{
	if ($action == "add") {
		add_route($subnet, $length, $gateway);
	} else if ($action == "del") {
		if ($subnet == "default")
			del_route("0.0.0.0", "0", $gateway, $dev);
		else
			del_route($subnet, $length, $gateway, $dev);
	} else {
		logging("SET_ROUTING : erreur d'argument!");
	}
}
function add_route($subnet, $length, $gateway)
{
	$str = "ip route add " . $subnet . "/" . $length . " via " . $gateway ;

	logging_debug($str);
	exec($str);
}

function del_route($subnet, $length, $gateway, $dev)
{
//	$str = "ip route del " . $subnet . "/" . $length . " via " . $gateway ;
	$str = "ip route del " . $subnet . "/" . $length ;
	if ($gateway != "")
		$str .= " via " . $gateway ;
	$str .= " dev " . $dev;
	logging_debug($str);
	exec($str);
}


?>
