<?php
include ('get_proc.inc.php');

function is_tango_finished()
{
        $filename = "/tmp/qos/qos.results";
        $handle = fopen($filename, "r+");
        if ($handle) {
                while (($buffer = fgets($handle, 4096)) !== false) {
                        $arr = explode(":", $buffer);
                        if ($arr[0] == "FIN") {
                                return "1";
                        }
                }
                if (!feof($handle)) {
                        return "1";
                }
                fclose($handle);
        } else {
                return "0";
        }

        return "0";
}


$nbflux = $_GET['nbflux'];
$status = is_tango_finished();

$tab = array();
$flow = array('debit', 'latence', 'perte');

for ($i = 0; $i < $nbflux ; $i++) {
	$tab[$i] = $flow;
}

$period = 1;

$rx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_packets");
$tx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_packets");

sleep($period);

$rx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_packets");
$tx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_packets");


$xrx_packets = $rx_packets[1]- $rx_packets[0];
$xtx_packets = $tx_packets[1]- $tx_packets[0];

$xrx_packets = $xrx_packets / $period;
$xtx_packets = $xtx_packets / $period;

$load = getServerLoad();
$load = round($load, 0);
$DataCpu = $load;






//Préparation de la réponse
//on ouvre la réponse
$reponse = '{ "status_detect":'.$status.', "DataCpu":'.$DataCpu.', "DataTrameRx":'.$xrx_packets.', "DataTrameTx":'.$xtx_packets. ', ' ;

for ($i=0; $i<$nbflux; $i++){
	$val = "";

	$lastline = system ("tail -n 1 /tmp/qos/flow-" . $i . "/tango.results | cut -d ' ' -f 13 > /tmp/qos/flow-" . $i . "/debit", $retval);
	$val = @file_get_contents("/tmp/qos/flow-" . $i . "/debit");
//	echo "=>" . $val;
	$tab[$i][0] = $val;



	// echo "===>" . $tab[$i][0] . "<====" ;
	$lastline = system ("tail -n 1 /tmp/qos/flow-" . $i . "/fping.results | cut -d ' ' -f 6 > /tmp/qos/flow-" . $i . "/latence");
	$val = @file_get_contents("/tmp/qos/flow-" . $i . "/latence");
//	echo "=>" . $val;
	if ($val == "min/avg/max\n")
		$val = "0";
	$tab[$i][1] = $val;



	$lastline = system ("tail -n 1 /tmp/qos/flow-" . $i . "/fping.results | cut -d ' ' -f 10 | tr -s '%' ' ' > /tmp/qos/flow-" . $i . "/pertes");
	$val = @file_get_contents("/tmp/qos/flow-" . $i . "/pertes");
	if ($val == "\n")
		$val = "0";
//	echo "=>" . $val . "<=";
	$tab[$i][2] = $val;




	
	// utilisation de variable dynamique
	// $Debit_flux_ = 'Debit_flux_'.$i;
	// $Latence_flux_ = 'Latence_flux_'.$i;
	// $Perte_flux_ = 'Perte_flux_'.$i;
	$n = $i+1;

	$reponse = $reponse . '"debit_flux_' . $n . '":' . trim($tab[$i][0]) . ', "latence_flux_' . $n . '":' . trim($tab[$i][1]) . ', "perte_flux_' . $n . '":' . trim($tab[$i][2]) . ', ';
}
//on supprime la derniére ,
$reponse = substr($reponse, 0, -2);
//on ferme la réponse
$reponse = $reponse.'}' ;

//on envoie la réponse
echo $reponse;

?>
