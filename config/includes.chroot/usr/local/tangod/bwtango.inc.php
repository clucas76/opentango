<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <l.leroy.ludovic@gmail.com>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

include_once('process.inc.php');

function tango_bwping($action, $pktsize, $debit, $ip, $ip_src, $duree, $tos)
{
	logging_debug("BWPING");
	$cmd = "";
	if ($action == "stop") {
		$monfichier = fopen('/tmp/tango.pid', 'r+');	
		$ligne = fgets($monfichier);
		fclose($monfichier);

		$cmd = "kill -9 " . $ligne ;
		logging_debug($cmd);
		exec($cmd);
		$cmd = "killall -KILL bwping " ;
		bgexec($cmd, "/dev/null");
		bgexec("rm /tmp/tango.pid", "/dev/null");
		$today = date("Y-m-d H-i-s");
		$cmd = "echo \"FIN:" . $today . "\" >> /tmp/tango.results";
		exec($cmd);
	} else if($action == "start") { 
		$vol = $debit * $duree * 1000 / 8 ;
//		$monip = get_ip_from_dev($dev);
		$monip = $ip_src;
		
		$cmd = "php /usr/local/tangod/bin/bwtango.php " . $debit . " " . $pktsize . " " . $vol . " " . $monip . " " . $ip . " " . $tos . " " . $duree;
		$pid = bgexec_append($cmd, "/dev/null");

		$monfichier = fopen('/tmp/tango.pid', 'w+');	
		fwrite($monfichier, $pid);
		fclose($monfichier);
	} else {
		logging("BWTANGO : ERREUR de format et d'action");
	}
}

function tango_save_pdf()
{
	$cmd = "cp -f /var/www/results/*.pdf /media/user/OPENTANGO/results/";
	exec($cmd);

}
?>
