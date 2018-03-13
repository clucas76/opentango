<?php

include_once('../comm.inc.php');


function show_route()
{
exec("ip route > /tmp/routes.tmp");

$handle = fopen("/tmp/routes.tmp", "r+");
if ($handle) {
	$i = 0;
    	while (($buffer = fgets($handle, 4096)) !== false) {

		$i++;
	}
    	if (!feof($handle)) {
        	echo "Erreur: fgets() a échoué\n";
    	}
    	fclose($handle);
}
$count = $i;

$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .=  "<rows>";
$s .= "<page>1</page>";
$s .= "<total>1</total>";
$s .= "<records>".$count."</records>";



$handle = fopen("/tmp/routes.tmp", "r+");
if ($handle) {
	$i = 1;
    	while (($buffer = fgets($handle, 4096)) !== false) {
//        echo $buffer;
    		$s .= "<row id='". $i ."'>";            
		$arr = explode(" ", $buffer);
		$tab = explode("/", $arr[0]);
 		
		$s .= "<cell></cell>";
		$s .= "<cell>". $tab[0] ."</cell>";
		$s .= "<cell>". $tab[1] ."</cell>";

		if ($arr[1] == "dev" ) {
			$s .= "<cell></cell>";
			$s .= "<cell>" . $arr[2] . "</cell>";
		}
		if ($arr[1] == "via") {
			$s .= "<cell>" . $arr[2] . "</cell>";
			$s .= "<cell>" . $arr[4] . "</cell>";
		}

  		$s .= "</row>";
	
		$i++;
    	}
    	if (!feof($handle)) {
        	echo "Erreur: fgets() a échoué\n";
    	}
    	fclose($handle);

	$s .= "</rows>"; 
 
	echo $s;

}

exec("rm -f /tmp/routes.tmp");
}

function del_route($id)
{
	exec("ip route > /tmp/routes.tmp");

	$i = 1;

	$handle = fopen("/tmp/routes.tmp", "r+");
	if ($handle) {
    		while (($buffer = fgets($handle, 4096)) !== false) {
			if ($i == $id) {
				$arr = explode(" ", $buffer);
				$ar2 = explode("/", $arr[0]);
				$subnet = $ar2[0];
				$cidr = $ar2[1];
				$cmd = "del:" . $subnet . ":" . $cidr . ":" ;

				if ($arr[1] == "dev" ) {
					$cmd .= ":" . $arr[2];
				}
				if ($arr[1] == "via" ) {
					$cmd .= $arr[2] . ":" . $arr[4];
				}

				exec("rm -f /tmp/routes.tmp");			
			
				return $cmd;
			}
	
			$i++;
		}
    		if (!feof($handle)) {
       		 	echo "Erreur: fgets() a échoué\n";
    		}
    		fclose($handle);
	}


}

/////////////////////////

$action 	= $_POST['oper'];
$id		= $_POST['id'];

if ($action == "del") {
	$cmd = del_route($id);		
	echo $cmd;

	send_to_tangod("route:" . $cmd . "");
} else {
	show_route();
}
