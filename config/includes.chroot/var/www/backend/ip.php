<?php

include_once('../comm.inc.php');


function show_ip()
{
	$cmd = "ls -l /sys/class/net | grep eth0 | tr -s \"\t\" \" \" | cut -d \" \" -f 9";
	exec($cmd, $output);

	$count = 1;
	foreach ($output as $value){
		$count++;
	}

	$s = "<?xml version='1.0' encoding='utf-8'?>";
	$s .=  "<rows>";
	$s .= "<page>1</page>";
	$s .= "<total>1</total>";
	$s .= "<records>".$count."</records>";

	$i = 1;

	foreach ($output as $value){
    		//commandes
		$arr = array();
		$cmd2 = "ip addr show dev " . $value . " | grep \"inet \" | tr -s \"\t\" \" \" | cut -d \" \" -f 3";
		exec($cmd2, $arr);

		foreach ($arr as $v) {

			$ar2 = explode("/", $v);
		
			$s .= "<row id='". $i ."'>";            

			$s .= "<cell></cell>";
			$s .= "<cell>". $ar2[0] ."</cell>";
			$s .= "<cell>". $ar2[1] ."</cell>";
			$s .= "<cell>". $value ."</cell>";

			$s .= "</row>";
			$i++;
		}
	}
	$s .= "</rows>"; 
 
	echo $s;
}

function del_ip($id)
{
	$cmd = "ls -l /sys/class/net | grep eth0 | tr -s \"\t\" \" \" | cut -d \" \" -f 9";
	exec($cmd, $output);
	$i = 1;
	// On parcourt les interfaces
	foreach ($output as $value){
    		//commandes
		$arr = array();

		// On parcourt les ip/cidr par interface
		$cmd2 = "ip addr show dev " . $value . " | grep \"inet \" | tr -s \"\t\" \" \" | cut -d \" \" -f 3";
		exec($cmd2, $arr);
		
		foreach ($arr as $v) {
			$ar2 = explode("/", $v);
			$cmd = "del:" . $ar2[0] . ":" . $ar2[1] . ":" . $value;
			if ($id == $i)
				return $cmd;

			$i++;
		}
	}
}

/////////////////////////

$action 	= $_POST['oper'];
$id		= $_POST['id'];

if ($action == "del") {
	$cmd = del_ip($id);		
	echo $cmd;

	send_to_tangod("ip:" . $cmd . "");
} else {
	show_ip();
}
