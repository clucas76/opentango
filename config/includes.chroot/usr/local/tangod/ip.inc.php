<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

function get_ip_from_dev($dev)
{
	$monip = exec("ip addr show dev " . $dev . " | grep \"inet \" | tr -s \"\t\" \" \" | cut -d ' ' -f 3 | cut -d \"/\" -f 1");
	return $monip;
}

function set_networking($action, $ip, $netmask, $dev)
{
	if ($action == "add") {
		add_ip_to_dev($ip, $netmask, $dev);
	} else if ($action == "del") {
		del_ip_to_dev($ip, $netmask, $dev);
	} else {
		logging("SET_NETWORKING : erreur d'argument!");
	}
}
function add_ip_to_dev($ip, $netmask, $dev)
{
	$str = "ip addr add " . $ip . "/" . $netmask . " dev " . $dev ;

	logging_debug($str);
	exec($str);
}

function del_ip_to_dev($ip, $netmask, $dev)
{
	$str = "ip addr del " . $ip . "/" . $netmask . " dev " . $dev ;
	logging_debug($str);
	exec($str);
}

function tangod_ping($ip)
{
	$str = "";
	$cmd = "ping -c 2  -W 1 " . $ip . " > /dev/null ; echo $?";
	$result = exec($cmd);
	if ($result != "0" ) {
		$str = "ATTENTION : La cible n'est pas joignable.\n";
	} else {
		$str = "OK :) Cible accessible.\n";
	} 
	logging_debug($str);
        $fp = stream_socket_client("tcp://127.0.0.1:1112", $errno, $errstr, 30);
        if (!$fp) {
                echo "$errstr ($errno)<br />\n";
        } else {
                fwrite($fp, "".$str."");
                fclose($fp);
        }
}


?>
