<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

include_once('process.inc.php');

function random($car)
{

	// 00:00:5E:00:00:01

        $string = "";
        $chaine = "0123456789abcdef";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
                $string .= $chaine[rand()%strlen($chaine)];
                if ($i == 1)
                        $string .= ":";
                if ($i == 3)
                        $string .= ":";
        }
        return $string;
}

function set_vlan($action, $id, $dev)
{
	logging_debug("SET_VLAN");

	$cmd = "";

	if ($action == "add") {
		// ip link add link eth0 name eth0.5 addr 00:1b:24:8b:26:3e type vlan id 5
		$macr = random(6);
		$mac = "00:00:5E:" . $macr;
		$cmd = "ip link add link " . $dev . " name " . $dev . "." . $id . " addr " . $mac . " type vlan id ". $id;
		bgexec($cmd, "/dev/null");
		$cmd = "ip link set " . $dev . "." . $id . " up"; 
		sleep(2);
		bgexec($cmd, "/dev/null");
	} else if($action == "del") { 

		$cmd = "ip link set " . $dev . "." . $id . " down" ;
		bgexec($cmd, "/dev/null");

		$cmd = "ip link delete " . $dev . "." . $id ;
		bgexec($cmd, "/dev/null");
	} else {
		logging("SET_VLAN : ERREUR de format et d'action");
	}
}
?>
