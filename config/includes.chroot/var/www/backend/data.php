<?php
include ('get_proc.inc.php');

session_start();
function is_tango_finished()
{
	$filename = "/tmp/tango.results";
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


$period = 1;

$rx[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_bytes");
$tx[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_bytes");

$rx_crc_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_crc_errors");
$rx_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_errors");
$tx_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_errors");
$collisions[] = @file_get_contents("/sys/class/net/eth0/statistics/collisions");
$rx_dropped[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_dropped");
$rx_fifo_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_fifo_errors");
$tx_dropped[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_dropped");
$tx_fifo_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_fifo_errors");
//modif
$rx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_packets");
$tx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_packets");
//modif


sleep($period);

$rx[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_bytes");
$tx[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_bytes");

$rx_crc_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_crc_errors");
$rx_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_errors");
$tx_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_errors");
$collisions[] = @file_get_contents("/sys/class/net/eth0/statistics/collisions");
$rx_dropped[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_dropped");
$rx_fifo_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_fifo_errors");
$tx_dropped[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_dropped");
$tx_fifo_errors[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_fifo_errors");
//modif
$rx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/rx_packets");
$tx_packets[] = @file_get_contents("/sys/class/net/eth0/statistics/tx_packets");
//modif


$tbps = $tx[1] - $tx[0];
$rbps = $rx[1] - $rx[0];

$xrx_crc_errors = $rx_crc_errors[1] - $rx_crc_errors[0];
$xrx_errors = $rx_errors[1] - $rx_errors[0];
$xtx_errors = $tx_errors[1] - $tx_errors[0];
$xcollisions = $collisions[1] - $collisions[0];
$xrx_dropped = $rx_dropped[1] - $rx_dropped[0];
$xrx_fifo_errors = $rx_fifo_errors[1] - $rx_fifo_errors[0];
$xtx_dropped = $tx_dropped[1] - $tx_dropped[0];
$xtx_fifo_errors = $tx_fifo_errors[1] - $tx_fifo_errors[0];

$DataRx=round(($rbps/1024)*8, 2);
$DataTx=round(($tbps/1024)*8, 2);



$DataRx = $DataRx / $period;
$DataTx = $DataTx / $period;


$xrx_packets = $rx_packets[1]- $rx_packets[0];
$xtx_packets = $tx_packets[1]- $tx_packets[0];

$xrx_packets = $xrx_packets / $period;
$xtx_packets = $xtx_packets / $period;

// $load = sys_getloadavg();
$load = getServerLoad();
$load = round($load, 0);
// $DataCpu = $load[0]*100;
$DataCpu = $load;

$status = is_tango_finished();

if ( $DataRx < 0)
{
	$DataRx = 0;
}

if ( $DataTx < 0)
{
	$DataTx = 0;
}


//modif
echo '
{"DataRx":'.$DataRx.', "DataTx":'. $DataTx .', "DataCpu":'.$DataCpu.', "Dataxrx_crc_errors":'. $xrx_crc_errors .', "Dataxrx_errors":'. $xrx_errors .', "Dataxtx_errors":'. $xtx_errors .', "Dataxcollisions":'. $xcollisions .', "Dataxrx_dropped":'. $xrx_dropped .', "Dataxrx_fifo_errors":'. $xrx_fifo_errors .', "Dataxtx_dropped":'. $xtx_dropped .', "Dataxtx_fifo_errors":'. $xtx_fifo_errors .', "DataTrameRx":'.$xrx_packets.', "DataTrameTx":'.$xtx_packets. ', "status_detect":'.$status.'}
';
//modif


?>
