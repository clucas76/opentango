<?php

function bgexec_append($str, $redir)
{
        $command = 'nohup '.$str.' >> ' . $redir . ' 2>&1 & echo $!';
        exec($command ,$op);
        $pid = (int)$op[0];
	return $pid;
}


function launcher($duree)
{

$tab_b64 = file_get_contents("/tmp/qos/tab_b64");

$j = base64_decode($tab_b64);
$arr = json_decode($j, true);

$nb = count($arr);
$i = 0;


$time_to_sleep = 0;
		foreach ($arr as $value){
			$tab = (array) $value;
			$tos		= $tab[tos];
			$debit 		= $tab[debit];
			$pkt_size 	= $tab[pkt_size];
			$ip_src 	= $tab[ip_src];
			$ip_dst 	= $tab[ip_dst];

			$vol = $debit * $duree * 1000 / 8 ;

			$cmd = "mkdir -p /tmp/qos/flow-" . $i . " && chown www-data.www-data /tmp/qos/flow-" . $i ;
			exec($cmd);
			echo $cmd . "\n";
			$cmd = "php /usr/local/tangod/bin/qos.php flow-" . $i  . " " . $debit . " " . $pkt_size . " " . $vol . " " . $ip_src . " " . $ip_dst . " " . $tos . " " . $duree;
			$pid = bgexec_append($cmd, "/dev/null");
			echo $cmd . "\n";
			$cmd2 = "echo " . $pid . ">> /tmp/qos/qos.php.pid";
			exec($cmd2);
			$i++;
		}	
		sleep($duree);
		$today = date("Y-m-d H:i:s");
        	$cmd = "echo \"FIN: " . $today . "\" >> /tmp/qos/qos.results";
		exec($cmd);
}

launcher($argv[1]);

?>
