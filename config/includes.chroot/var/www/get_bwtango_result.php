<?php
$filename = "/tmp/tango.results";
$handle = fopen($filename, "r+");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
	$arr = explode(":", $buffer);
	if ($arr[0] == "FIN") {
		echo "finished:" . $arr[1];
		exit(0);
	} 
    }
    if (!feof($handle)) {
        echo "Erreur: fgets() a échoué\n";
    }
    fclose($handle);
} else {
	echo "probleme de handler!";
}
echo "started::::::"

?>
