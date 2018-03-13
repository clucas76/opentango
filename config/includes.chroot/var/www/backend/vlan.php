<?php

include_once('../comm.inc.php');


function show_vlan()
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

		$ar2 = explode(".", $value);

		$vlanid = "";
		if ($ar2[1] == "")
			$vlanid = "natif";
		else
			$vlanid = $ar2[1];
		
		$s .= "<row id='". $i ."'>";            

		$s .= "<cell></cell>";
		$s .= "<cell>". $vlanid ."</cell>";
		$s .= "<cell>". $value ."</cell>";

		$s .= "</row>";
		$i++;
	}
	$s .= "</rows>"; 
 
	echo $s;
}

function del_vlan($id)
{
	$cmd = "ls -l /sys/class/net | grep eth0 | tr -s \"\t\" \" \" | cut -d \" \" -f 9";
	exec($cmd, $output);
	$i = 1;
	// On parcourt les interfaces
	foreach ($output as $value){
    		//commandes
		$arr = array();
		$arr = explode(".", $value);

		$cmd = "del:" . $arr[1] . ":eth0";

		if ($id == $i)
			return $cmd;
		
		$i++;
	}
}

/////////////////////////

$action 	= $_POST['oper'];
$id		= $_POST['id'];

if ($action == "del") {
	$cmd = del_vlan($id);		
	echo $cmd;

	send_to_tangod("vlan:" . $cmd . "");
} else {
	show_vlan();
}
