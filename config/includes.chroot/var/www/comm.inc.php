<?php
function send_to_tangod($str)
{
	$fp = stream_socket_client("tcp://127.0.0.1:1111", $errno, $errstr, 30);
	if (!$fp) {
    		echo "$errstr ($errno)<br />\n";
	} else {
		$pieces = str_split($str, 1024 * 4);
    		foreach ($pieces as $piece) {
        		fwrite($fp, "" . $piece . "" , strlen($piece));
    		}

//		fwrite($fp, "".$str."");

		fclose($fp);
	}
}

function read_from_tangod()
{
	$ret = "";
	$socket = stream_socket_server("tcp://127.0.0.1:1112", $errno, $errstr);
	if (!$socket) {
		echo "$errstr ($errno)<br />\n";
	} else {
		// Je ne veux qu'une connexion retour depuis tangod
  		$conn = stream_socket_accept($socket) ;
		while (!feof($conn)) {
        		$ret .= fgets($conn, 1024);
    		}
  		fclose($socket);
	}
	return $ret;
}

?>
