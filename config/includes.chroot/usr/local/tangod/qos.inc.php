<?php
//
// Tangod -- Copyright 2014-2017 : 
// Christophe Lucas <christophe@opentango.net>
// Ludovic Leroy <ludovic@opentango.net>
// 
// Lanceur du programme de test de QOS
//

include_once('process.inc.php');

function qospid_to_array()
{
	$array = file("/tmp/qos/qos.php.pid", FILE_IGNORE_NEW_LINES);
	$c = count($array);
	return $array;
}

function qos($action, $duree, $tab_b64)
{
	$cmd = "";
	logging_debug("QOS:qos();");
	if ($action == "stop") {
		$cmd = "killall -KILL fping" ;
		logging_debug($cmd);
		bgexec($cmd, "/dev/null");
		$cmd = "killall -KILL bwping " ;
		bgexec($cmd, "/dev/null");

		$arr = qospid_to_array();
		foreach ($arr as $value){
			$cmd3 = "kill -9 " . $value;
			exec($cmd3);
		}

		$today = date("Y-m-d H-i-s");
		$cmd = "echo \"FIN:" . $today . "\" >> /tmp/qos.results";
		exec($cmd);
	} else if($action == "start") { 


		$today = date("Y-m-d H:i:s");
        	// Afin de faire que apache/ajax puisse lire le fichier
        	$cmd = "rm -rf /tmp/qos* && mkdir -p /tmp/qos/ && chown www-data.www-data /tmp/qos";
        	exec($cmd);
        	$cmd = "echo \"DEBUT: " . $today . "\" > /tmp/qos/qos.results && chown www-data.www-data /tmp/qos/qos.results";
        	exec($cmd);
		exec("echo " . $tab_b64 . "> /tmp/qos/tab_b64");
		$ppid = bgexec("/usr/bin/php /usr/local/tangod/bin/qos_scheduler.php " . $duree, "/dev/null"); 
		sleep(2);
		exec("echo " . $ppid . ">> /tmp/qos/qos.php.pid");	
	} else {
		logging("QOS : ERREUR de format et d'action");
	}
}

?>
