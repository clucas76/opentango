<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

include_once('process.inc.php');

function bwping($action, $pktsize, $debit, $ip, $dev)
{
	logging_debug("BWPING");

	$cmd = "";

	if ($action == "stop") {
		$monfichier = fopen('/tmp/bwping.pid', 'r+');	
		$ligne = fgets($monfichier);
		fclose($monfichier);

		$cmd = "kill -9 " . $ligne;
		bgexec($cmd, "/dev/null");
		bgexec("killall -KILL bwping", "/dev/null");
		bgexec("rm /tmp/bwping.pid", "/dev/null");
	} else if($action == "start") { 

		$vol = $debit * 100 * 1000 / 8 ;
		$monip = get_ip_from_dev($dev);
		$cmd = "bwping -b " . $debit . " -s " . $pktsize . " -v " . $vol . " -B " . $monip . " " . $ip ;
		$pid = bgexec($cmd, "/tmp/bwping.results");
		logging_debug("BWPING PID= ". $pid);
		$monfichier = fopen('/tmp/bwping.pid', 'w+');	
		fwrite($monfichier, $pid);
		fclose($monfichier);

	} else {
		logging("BWPING : ERREUR de format et d'action");
	}

	
}
?>
