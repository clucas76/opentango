<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

// php bin/bwtango.php start 64_128_256 100 217.169.242.186 eth0


function bgexec_append($str, $redir)
{
        $command = 'nohup '.$str.' >> ' . $redir . ' 2>&1 & echo $!';
        exec($command ,$op);
}


function tango_bwping($debit, $pktsize, $vol, $monip, $ip_dest, $tos, $duree)
{
	$today = date("Y-m-d H:i:s");

	// Afin de faire que apache/ajax puisse lire le fichier
	$cmd = "rm -f /tmp/tango.results && > /tmp/tango.results && chown www-data.www-data /tmp/tango.results";
	exec($cmd);
	$cmd = "rm -f /tmp/fping.results && > /tmp/fping.results && chown www-data.www-data /tmp/fping.results";
	exec($cmd);

	$cmd = "echo \"DEBUT: " . $today . "\" > /tmp/tango.results";
	exec($cmd);
//	$vol = $debit * 100 * 1000 / 8 ;
	

	$cmd = "> /tmp/bwdebit.out";
	exec($cmd);

	$arr = explode("_", $pktsize);
	foreach ($arr as $value){

		$cmd = "bash /usr/local/tangod/bin/get_tx.sh >> /tmp/bwdebit.out && echo -n \" \" >> /tmp/bwdebit.out";
		exec($cmd);

		$pktcount = ($duree * 0.95) * 10;
		$cmd = "fping -q -O " . $tos . " -c " . $pktcount . " -b 64 -p 100 -i 5 " . $ip_dest;
		bgexec_append($cmd, "/tmp/fping.results");

		$cmd = "bwping -T " . $tos . " -b " . $debit . " -s " . $value . " -v " . $vol . " -B " . $monip . " " . $ip_dest ;
		$pid = exec($cmd . " >> /tmp/tango.results 2>&1");
		$monfichier = fopen('/tmp/tango.pid', 'w+');	
		fwrite($monfichier, $pid);
		fclose($monfichier);

		$cmd = "bash /usr/local/tangod/bin/get_tx.sh >> /tmp/bwdebit.out && echo \"\" >> /tmp/bwdebit.out";
		exec($cmd);

		
		$cmd = "/bin/grep -v ' No buffer' /tmp/tango.results > /tmp/tango2 && cp /tmp/tango2 /tmp/tango.results && chown www-data.www-data /tmp/tango.results";
		bgexec_append($cmd, "/dev/null");

		sleep(2);

		
	}
	$today = date("Y-m-d H:i:s");
	$cmd = "echo \"FIN: " . $today . "\" >> /tmp/tango.results";
	exec($cmd);
	$cmd = "rm -f /tmp/tango.pid";
	exec($cmd);
}

pcntl_signal(SIGHUP,  function($signo) {
	echo "Gestionnaire de signal appelé!\n";
	exit(255);
});


echo "=>" . $argv[1] . " " . $argv[2] . " " . $argv[3] . " " . $argv[4] . "\n"; 

tango_bwping($argv[1], $argv[2], $argv[3], $argv[4], $argv[5], $argv[6], $argv[7]);
?>