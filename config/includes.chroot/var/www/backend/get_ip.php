<?php

$outpur = array();

$cmd = "ls -l /sys/class/net | grep eth0 | tr -s \"\t\" \" \" | cut -d \" \" -f 9";
exec($cmd, $output);

//[ {"optionValue":10, "optionDisplay": "Remy"},
// {"optionValue":11, "optionDisplay": "Arif"},
//{"optionValue":12, "optionDisplay": "JC"}]

$result = "[ ";

foreach ($output as $value){
    	//commandes
	$arr = array();
	$cmd2 = "ip addr show dev " . $value . " | grep \"inet \" | tr -s \"\t\" \" \" | cut -d \" \" -f 3 | cut -d \"/\" -f 1";
	exec($cmd2, $arr);

	$result = $result . "{\"optionValue\":\"" . $arr[0] . "\", \"optionDisplay\": \"" . $arr[0] . "(" . $value . ")\"},";
}
$result = rtrim($result, ",");
$result .= "]";
echo $result;
?>
