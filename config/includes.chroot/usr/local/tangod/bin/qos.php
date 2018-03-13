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
        $command = 'taskset 0x1 stdbuf -oL nohup '.$str.' >> ' . $redir . ' 2>&1 & echo $!';
        exec($command ,$op);
        $pid = (int)$op[0];
	return $pid;
}


function disable_ob() {
    // Turn off output buffering
    ini_set('output_buffering', 'off');
    // Turn off PHP output compression
    ini_set('zlib.output_compression', false);
    // Implicitly flush the buffer(s)
    ini_set('implicit_flush', true);
    ob_implicit_flush(true);
    // Clear, and turn off output buffering
    while (ob_get_level() > 0) {
        // Get the curent level
        $level = ob_get_level();
        // End the buffering
        ob_end_clean();
        // If the current level has not changed, abort
        if (ob_get_level() == $level) break;
    }
    // Disable apache output buffering/compression
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
        apache_setenv('dont-vary', '1');
    }
}

function qos_bwping($flow, $debit, $pktsize, $vol, $monip, $ip_dest, $tos, $duree)
{
	$pktcount = ($duree * 0.95) * 10;
	$cmd = "fping -O " . $tos . " -c " . $pktcount . " -b 64 -p 100 -i 5 -S " . $monip  . " " . $ip_dest;
	bgexec_append($cmd, "/tmp/qos/" . $flow . "/fping.results");

	$cmd = "bwping -r 1 -T " . $tos . " -b " . $debit . " -s " . $pktsize . " -v " . $vol . " -B " . $monip . " " . $ip_dest ;
	$pid = bgexec_append($cmd, "/tmp/qos/" . $flow . "/tango.results 2>&1");
	sleep($duree);
	$cmd2 = "kill -USR1 " . $pid;
	exec($cmd2);

	$cmd = "/bin/grep -v ' No buffer' /tmp/qos/" . $flow . "/tango.results > /tmp/tango2 && cp /tmp/tango2 /tmp/qos/" . $flow . "/tango.results && chown -R www-data.www-data /tmp/qos/" . $flow . "/";
	bgexec_append($cmd, "/dev/null");

}

pcntl_signal(SIGHUP,  function($signo) {
	echo "Gestionnaire de signal appelé!\n";
	exit(255);
});


echo "=>" . $argv[1] . " " . $argv[2] . " " . $argv[3] . " " . $argv[4] . "\n"; 

disable_ob();
qos_bwping($argv[1], $argv[2], $argv[3], $argv[4], $argv[5], $argv[6], $argv[7], $argv[8]);
?>
