<?php

if(!class_exists('SQLite3'))
  die("SQLite 3 NOT supported.");

$base = new SQLite3("opentango.db");

if ($_GET['cmd'] == "add") {
	$ip_src 	= $_GET['ip_src'];
	$ip_dst 	= $_GET['ip_dst'];
	$tos	 	= $_GET['tos'];
	$debit	 	= $_GET['debit'];
	$pkt_size 	= $_GET['pkt_size'];
	
	$query = "INSERT INTO \"flux\" (\"id\",\"tos\",\"debit\",\"pkt_size\",\"ip_src\",\"ip_dst\") VALUES( NULL, '" . $tos . "', '" . $debit  . "','" . $pkt_size . "', '" . $ip_src . "','" . $ip_dst . "');"  ;
	$results = $base->query($query);
	echo "";

} else if ($_GET['cmd'] == "del") {
	$id	= $_GET['id']; 
	$query = "DELETE FROM flux WHERE id = '" . $id . "';";
	$results = $base->query($query);
	echo "";

} else {
	$s = "<?xml version='1.0' encoding='utf-8'?>";
	$s .=  "<rows>";
	$s .= "<page>1</page>";
	$s .= "<total>1</total>";
	$s .= "<records>".count($row)."</records>";

	$i = 0;

	$query = "SELECT * FROM flux;";
	$results = $base->query($query);
	while ($row = $results->fetchArray()) {
	   	$id 		= $row['id'];
  		$tos 		= $row['tos']; 
   		$debit 		= $row['debit'];
   		$pkt_size 	= $row['pkt_size'];
   		$ip_src 	= $row['ip_src'];
		$ip_dst 	= $row['ip_dst'];
 

		$s .= "<row id='". $i ."'>";            

		$s .= "<cell></cell>";
		$s .= "<cell>". $id ."</cell>";
		$s .= "<cell>". $tos ."</cell>";
		$s .= "<cell>". $debit ."</cell>";
		$s .= "<cell>". $pkt_size ."</cell>";
		$s .= "<cell>". $ip_src ."</cell>";
		$s .= "<cell>". $ip_dst ."</cell>";

		$s .= "</row>";
		$i++;
	}

	$s .= "</rows>";
	echo $s;
}


?>
