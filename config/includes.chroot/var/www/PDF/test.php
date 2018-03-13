<?php
$cmd = "";
$i="1";
unlink('/tmp/qos/qos_tableau.txt');
	for($i=1; $i<3; $i++){
		$nom		= $i;
		$tos		= "tos";
		$pkt_size 	= "pkt_size";	
		$debit 		= "debit";
		$ip_src		= "ip_src";
		$ip_dst		= "ip_dst";
		$monfichier = fopen('/tmp/qos/qos_tableau.txt', 'a');		
		$cmd = "chown www-data.www-data /tmp/qos/qos_tableau.txt";
		exec($cmd);
		$ecrire = $nom . ":" . $tos . ":" . $pkt_size . ":" . $debit . ":" . $ip_src . ":" . $ip_dst . "\n";
        	fwrite($monfichier, $ecrire);
	}

?>