<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//


include_once('ip.inc.php');
include_once('negociation.inc.php');
include_once('bwping.inc.php');
include_once('vlan.inc.php');
include_once('bwtango.inc.php');
include_once('route.inc.php');
include_once('ip_auto.inc.php');
include_once('qos.inc.php');

function logging($str)
{
	global $log;

	$today = date("Y-m-d H:i:s");
	file_put_contents($log, $today . " >> " . $str . "\n", FILE_APPEND);
}

function logging_debug($str)
{
	global $debug, $log;

	$today = date("Y-m-d H:i:s");
	if ($debug == 1 ) 
		file_put_contents($log, $today . " >> DEBUG : " . $str . "\n", FILE_APPEND);
}



function handle_client($sock, $conn, $log)
{
	$input = socket_read($conn, 4096) or die("handle_client() >> Could not read input\n");
	// clean up input string
	$input = trim($input);
	$arr = explode(":", $input);

	logging_debug($arr[0] . "===>" . $input . "\n");
	switch($arr[0]) {
		case "ip" : 	logging_debug("ACTION_IP\n");
				$action		= $arr[1];
				$ip 		= $arr[2];
				$netmask	= $arr[3];
				$dev		= $arr[4];
				set_networking($action, $ip, $netmask, $dev);
				break;

		case "route" : 	logging_debug("ACTION_ROUTE\n");
				$dev = "";

				$action		= $arr[1];
				$subnet 	= $arr[2];
				$length		= $arr[3];
				$gateway	= $arr[4];
				if ($action != "add")
					$dev		= $arr[5];
				set_routing($action, $subnet, $length, $gateway, $dev);
				break;

		case "nego":	
				logging_debug("ACTION_NEGO\n");
				$vitesse 	= $arr[1];
				$duplex		= $arr[2];
				$dev		= $arr[3];
				$auto_b		= $arr[4];
				$auto 		= 0;
				if ($auto_b == "true")
					$auto = 1;
					
				set_negociation($vitesse, $duplex, $dev, $auto);
				break;
		case "ip_auto":	
				logging_debug("ACTION_IP_AUTO\n");
				$value	 	= $arr[1];
					
				set_ip_auto($value);
				break;

		case "vlan":	
				logging_debug("ACTION_VLAN\n");
				$action	 	= $arr[1];
				$id	 	= $arr[2];
				$dev		= $arr[3];
				set_vlan($action, $id, $dev);
				break;

		case "etherate":
				logging_debug("ACTION_ETHERATE\n");
				$vitesse 	= $arr[1];
				$duplex		= $arr[2];
				$auto		= $arr[3];
				$mode		= $arr[4]; // SERVEUR / CLIENT
				etherate($ip, $netmask, $gateway);

				break;
		case "bwping":
				logging_debug("ACTION_BWPING\n");
				$action 	= $arr[1];
				$pktsize 	= $arr[2];;
				$debit		= $arr[3];
				$ip		= $arr[4];
				$dev		= $arr[5];
				bwping($action, $pktsize, $debit, $ip, $dev);

				break;
		case "tango":
				logging_debug("ACTION_TANGO");
				$action 	= $arr[1];
				$pktsize = $debit = $ip = $ip_src = $duree = $tos = "";
				if ($action == "start") {
					$pktsize 	= $arr[2];
					$debit		= $arr[3];
					$ip		= $arr[4];
					$ip_src		= $arr[5];
					$duree		= $arr[6];
					$tos		= $arr[7];
				} else if ($action == "savepdf") {
					tango_save_pdf();
					break;
				} 
				tango_bwping($action, $pktsize, $debit, $ip, $ip_src, $duree, $tos);
				break;
		case "iperf":
				logging_debug("ACTION_IPERF");
				$ip	 	= $arr[1];
				$tcpudp		= $arr[2];
				$debit		= $arr[3];
				$mode		= $arr[4]; // SERVEUR / CLIENT
				iperf($ip, $netmask, $gateway);

				break;
		case "ping":
				logging_debug("ACTION_PING");
				$ip	 	= $arr[1];

				tangod_ping($ip);
				
				break;
		case "qos":	
				logging_debug("ACTION_QOS");
				$action 	= $arr[1];
				$duree		= $arr[2];
				$tab_b64	= $arr[3];
				qos($action, $duree, $tab_b64);
				break ;
		default: 	logging("pas d'action\n");
	}
}


?>
