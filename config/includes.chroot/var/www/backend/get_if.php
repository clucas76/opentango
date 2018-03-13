<?php

$output = array();

$cmd = "ls -l /sys/class/net | grep eth0 | tr -s \"\t\" \" \" | cut -d \" \" -f 9";
exec($cmd, $output);

//[ {"optionValue":10, "optionDisplay": "Remy"},
// {"optionValue":11, "optionDisplay": "Arif"},
//{"optionValue":12, "optionDisplay": "JC"}]

$result = "[ ";

foreach ($output as $value){
	$result = $result . "{\"optionValue\":\"" . $value . "\", \"optionDisplay\": \"" . $value . "\"},";
}
$result = rtrim($result, ",");
$result .= "]";
echo $result;
?>
