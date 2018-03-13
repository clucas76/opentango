<?php
function nbflux_php()
{
	$tab 	= $_POST["qos_tab"];
	$json_tab = base64_decode($tab);
	$flux_recut = count(json_decode($json_tab));
	return $flux_recut;
}

function tos_to_dscp($value)
{
	$v = "";
	switch($value) {
		case 0:
			return "0";
			break;
		case 32:
			return "CS1";
			break;
		case 40:
			return "AF11";
			break;
		case 48:
			return "AF12";
			break;
		case 64:
			return "CS2";
			break;
		case 72:
			return "AF21";
			break;
		case 80:
			return "AF22";
			break;
		case 96:
			return "CS3";
			break;
		case 104:
			return "AF31";
			break;
		case 112:
			return "AF32";
			break;
		case 128:
			return "CS4";
			break;
		case 136:
			return "AF41";
			break;
		case 144:
			return "AF42";
			break;
		case 184:
			return "EF";
			break;

	}
}

?>
