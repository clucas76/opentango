#!/usr/bin/php
 
<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <l.leroy.ludovic@gmail.com>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

include_once('handler.inc.php');
 
$log = '/var/log/tangod.log';
$debug = 1;
 
/**
 * Method for displaying the help and default variables.
 **/
function displayUsage(){
    global $log;
 
    echo "\n";
    echo "TangoD Daemon\n";
    echo "\n";
    echo "Usage:\n";
    echo "\ttangod.php [options]\n";
    echo "\n";
    echo "\toptions:\n";
    echo "\tt--help display this help message\n";
    echo "\tt--log=<filename> The location of the log file (default '$log')\n";
    echo "\n";
}//end displayUsage()
 
//configure command line arguments
if($argc > 0){
    foreach($argv as $arg){
        $args = explode('=',$arg);
        switch($args[0]){
            case '--help':
                return displayUsage();
            case '--log':
                $log = $args[1];
                break;
        }//end switch
    }//end foreach
}//end if
 
//fork the process to work in a daemonized environment
logging("Status: starting up.");
$pid = pcntl_fork();
if($pid == -1){
	logging("Error: could not daemonize process.");
	return 1; //error
}
else if($pid){
	exec("echo \"" . $pid . "\" > /var/run/tangod.pid");
	return 0; //success
}
else{
    	//the main process

	$port = 1111;

	// create a streaming socket, of type TCP/IP
    	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
   
    	// set the option to reuse the port
    	socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
   
    	// "bind" the socket to the address to "localhost", on port $port
    	// so this means that all connections on this port are now our resposibility to send/recv data, disconnect, etc..
    	socket_bind($sock, 0, $port);
   
    	// start listen for connections
    	socket_listen($sock);


	$clients = array($sock);


	while ( true ) {

		$read = $clients;

		$connection = socket_accept($sock);
		if ($connection === false) {
			usleep(100);
        	} elseif ($connection > 0) {
            		handle_client($sock, $connection, $log);
        	} else {
            		echo "error: ".socket_strerror($connection);
            		die;
        	} 
	} // while
}//end if
 
?>



