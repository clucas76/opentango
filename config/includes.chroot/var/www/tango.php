<?php

include_once('comm.inc.php');

$mode 		= $_POST['radio_mode'];
$ip		= $_POST['ipv4_target'];
$duree 		= $_POST['duree'];
$debit 		= $_POST['debit'];
$ip_src		= $_POST['select_ip_src'];
$pktsize	= "";
$tos		= $_POST['tos'];
$nego		= $_POST['nego_hidden'];

foreach($_POST['pktsize'] as $item)
{ 
	// query to delete where item = $item 
	$pktsize = $pktsize . "_" . $item;
} 
$pktsize = ltrim($pktsize, "_");

$cmd = "tango:start:" . $pktsize . ":" . $debit . ":" . $ip . ":" . $ip_src . ":" . $duree . ":" . $tos;

$str_tango_infos = "";
if ($mode == "testeur") {
	$str_tango_infos = "Mode : Testeur\nSource : " . $ip_src . "\nDestination : " . $ip . "\nD&eacute;bit : " . $debit . " kbits\nDur&eacute;e par cycle : " . $duree . " s\nTaille des paquets : " . $pktsize . "\nTOS : " . $tos . "";
} else {
	$str_tango_infos = "Mode : boucleur\nMon IP : " . $ip_src ."\n";	
}

// echo $cmd;
//start:64_128_256_512:400:217.169.242.186:eth0:130 

if (file_exists("/tmp/tango.pid")) {
?>
<html>
<body>
<center><h1>Il y a d&eacute;j&agrave; en cours</h1>><center>
<center><a href="/tango/">[[ RETOUR ]]</a></center>
</body>
</html>
<?php
	exit (0);
} else {
	// Si on est en mode testeur, on lance le test sinon non.
	if ($mode == "testeur")
		send_to_tangod($cmd);
}
?>

<html>
    <head>
	<link rel="stylesheet" href="js/theme/jquery-ui.css">

       <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/js/flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.time.js"></script>    
<script type="text/javascript" src="js/flot/jshashtable-2.1.js"></script>    
<script type="text/javascript" src="js/flot/jquery.numberformatter-1.2.3.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.symbol.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.axislabels.js"></script>
	<script src="js/jquery-ui/jquery-ui.js"></script>
<script>
function stop_tango()
{
        $.ajax({
                url: 'stop_tango.php',
                type: 'GET',
                dataType: "text",
                success: function(data, status, xr) {
			// status:test_nr:nr:pktsize:bandwidth:duree:
				$( "#infos-results" ).html("Test Tango annul&eacute;.");
				VarStop = 0;
				top.location.replace("/");
                },
                error: function(e) {
                        //called when there is an error
                        //console.log(e.message);
                }
        });
}


function generate_pdf(nom_client, lieu, nom_tech, ip_src, ip_dst, debit, paquet, tpc, tos, nego)
{
        $.ajax({
                url: './PDF/generationPdf.php',
                type: 'GET',
                dataType: "text",
		data: "nom_client="+ nom_client + "&lieu=" + lieu + "&nom_tech=" + nom_tech + "&ip_src=" + ip_src + "&ip_dst=" + ip_dst + "&debit=" + debit + "&paquet=" + paquet + "&tpc=" + tpc + "&tos=" + tos + "&nego=" + nego,
                success: function(data, status, xr) {
			var w = window.open(data, "RESULTATS", "resizable,scrollbars,status");
			top.location.replace("/");
		},
		error: function(e) {
                        //called when there is an error
                        //console.log(e.message);
                }
        });
}


<?php
if ($mode != "testeur") 
	echo "var mode = 0;";
else
	echo "var mode = 1;";
?>

// Si on est en mode testeur, on checke le resultat, sinon pas de get_results()
// if (mode == 1)
//	get_results();

var VarStop = 1;

function FonctionStart()
{
	VarStop = 1;
}

function FonctionStop()
{
      	VarStop = 0;
}


var DataRx = [], DataTx = [], DataCpu = [], Dataxrx_crc_errors = [], Dataxrx_errors = [], Dataxtx_errors = [], Dataxcollisions = [], Dataxrx_dropped = [], Dataxrx_fifo_errors = [], Dataxtx_dropped = [], Dataxtx_fifo_errors = [], DataTrameRx = [], DataTrameTx = [];
 
var dataset_debit,dataset_cpu,dataset_error,dataset_pakets;
var min = <?php echo $duree;?>;
var totalPoints = (min*60)/8;
var updateInterval = 1000;
var now = new Date().getTime();

var options_debit = {
    series: {
        lines: {
            lineWidth: 1.2
        }
    },
    xaxis: {
        mode: "time",
       
        axisLabel: "Time",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
    },
    yaxes: [
        {
            axisLabel: "Debit",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 6
        }
    ],
    legend: {
        noColumns: 0,
        position:"nw"
    },
    grid: {      
        backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
    }
};

var options_cpu = {
    series: {
        lines: {
            lineWidth: 1.2
        }
    },
    xaxis: {
        mode: "time",

        axisLabel: "Time",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
    },
    yaxes: [
        {
            min: 0,
            max: 100,
            tickSize: 5,
            tickFormatter: function (v, axis) {
                if (v % 20 == 0) {
                    return v + "%";
                } else {
                    return "";
                }
            },
            axisLabel: "CPU loading",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 6
        }
    ],
    legend: {
        noColumns: 0,
        position:"nw"
    },
    grid: {      
        backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
    }
};



var options_error = {
    series: {
        lines: {
            lineWidth: 1.2
        }
    },
    xaxis: {
        mode: "time",
        
        axisLabel: "Time",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
    },
    yaxes: [
        {
            axisLabel: "Error",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 6
        }
    ],
    legend: {
        noColumns: 0,
        position:"nw"
    },
    grid: {      
        backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
    }
};

var options_pakets = {
   series: {
        lines: {
            lineWidth: 1.2
        }
    },
    xaxis: {
        mode: "time",
        
        axisLabel: "Time",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
    },
    yaxes: [
        {
            axisLabel: "Packets/s",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 6
        }
    ],
    legend: {
        noColumns: 0,
        position:"nw"
    },
    grid: {      
        backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
    }
};

function initData() 
{
    	for (var i = 0; i < totalPoints; i++) {
        	var temp = [now , 0];

        	DataRx.push(temp);
        	DataTx.push(temp);
        	DataCpu.push(temp);
        	Dataxrx_crc_errors.push(temp);
      	  	Dataxrx_errors.push(temp);
        	Dataxtx_errors.push(temp);
        	Dataxcollisions.push(temp);
        	Dataxrx_dropped.push(temp);
        	Dataxrx_fifo_errors.push(temp);
        	Dataxtx_dropped.push(temp);
        	Dataxtx_fifo_errors.push(temp);
		DataTrameRx.push(temp);
		DataTrameTx.push(temp);
    	}
}

function GetData() 
{
    	$.ajaxSetup({ cache: false });

    	$.ajax({
        	url: "./backend/data.php",
       	 	dataType: 'json',
        	success: update,
        	error: function () {
            		setTimeout(GetData, updateInterval);
        	}
    	});
}

var temp;

function update(_data) {

if(VarStop == 1){
    DataRx.shift();
    DataTx.shift();
    DataCpu.shift();
    Dataxrx_crc_errors.shift();
    Dataxrx_errors.shift();
    Dataxtx_errors.shift();
    Dataxcollisions.shift();
    Dataxrx_dropped.shift();
    Dataxrx_fifo_errors.shift();
    Dataxtx_dropped.shift();
    Dataxtx_fifo_errors.shift();
    DataTrameRx.shift();
    DataTrameTx.shift();

   var now = new Date().getTime();

    temp = [now, _data.DataRx];
    DataRx.push(temp);

    temp = [now, _data.DataTx];
    DataTx.push(temp);

    temp = [now, _data.DataCpu];
    DataCpu.push(temp);

    temp = [now, _data.Dataxrx_crc_errors];
    Dataxrx_crc_errors.push(temp);

    temp = [now, _data.Dataxrx_errors];
    Dataxrx_errors.push(temp);

    temp = [now, _data.Dataxtx_errors];
    Dataxtx_errors.push(temp);

    temp = [now, _data.Dataxcollisions];
    Dataxcollisions.push(temp);

    temp = [now, _data.Dataxrx_dropped];
    Dataxrx_dropped.push(temp);

    temp = [now, _data.xrx_fifo_errors];
    Dataxrx_fifo_errors.push(temp);

    temp = [now, _data.Dataxtx_dropped];
    Dataxtx_dropped.push(temp);

    temp = [now, _data.Dataxtx_fifo_errors];
    Dataxtx_fifo_errors.push(temp);

    temp = [now, _data.DataTrameRx];
    DataTrameRx.push(temp);

    temp = [now, _data.DataTrameTx];
    DataTrameRx.push(temp);


    dataset_debit = [
        { label: "Data Rx:" + _data.DataRx + "Kb/s", data: DataRx, lines: { fill: true, lineWidth: 1.2 }, color: "#00FF00" },
        { label: "Data Tx:" + _data.DataTx + "Kb/s", data: DataTx, lines: { lineWidth: 1.2}, color: "#FF0000" }        
    ];

    dataset_cpu = [
        { label: "CPU:" + _data.DataCpu + "%", data: DataCpu, lines: { fill: true, lineWidth: 1.2 }, color: "#00FF00" }       
    ];

    dataset_error = [
        { label: "crc:" + _data.Dataxrx_crc_errors + "pps:", data: Dataxrx_crc_errors, lines: { fill: true, lineWidth: 1.2 }, color: "#111111" },
{ label: "Errors Rx:" + _data.Dataxrx_errors + "pps:", data: Dataxrx_errors, lines: { fill: true, lineWidth: 1.2 }, color: "#3A8EBA" },
{ label: "Errors Tx:" + _data.Dataxtx_errors + "pps:", data: Dataxtx_errors, lines: { fill: true, lineWidth: 1.2 }, color: "#ED0000" },
{ label: "collisions:" + _data.Dataxcollisions + "pps:", data: Dataxcollisions, lines: { fill: true, lineWidth: 1.2 }, color: "#FD3F92" },
{ label: "Dropped Rx:" + _data.Dataxrx_dropped + "pps:", data: Dataxrx_dropped, lines: { fill: true, lineWidth: 1.2 }, color: "#FDEE00" },
{ label: "Fifo Errors Rx:" + _data.Dataxrx_fifo_errors + "pps:", data: Dataxrx_fifo_errors, lines: { fill: true, lineWidth: 1.2 }, color: "#00FF00" },
{ label: "Dropped Tx:" + _data.Dataxtx_dropped + "pps:", data: Dataxtx_dropped, lines: { fill: true, lineWidth: 1.2 }, color: "#FF7F00" },
{ label: "Fifo Errors Tx:" + _data.Dataxtx_fifo_errors + "pps:", data: Dataxtx_fifo_errors, lines: { fill: true, lineWidth: 1.2 }, color: "#9E9E9E" }
    ];

    dataset_pakets = [
        { label: "Paquets Rx:" + _data.DataTrameRx + "Packets/s", data: DataTrameRx, lines: { fill: true, lineWidth: 1.2 }, color: "#00FF00" },
        { label: "Paquets Tx:" + _data.DataTrameTx + "Packets/s", data: DataTrameTx, lines: { lineWidth: 1.2}, color: "#FF0000" }        
    ];

    	$.plot($("#flot-placeholder_debit"), dataset_debit, options_debit);
    	$.plot($("#flot-placeholder_cpu"), dataset_cpu, options_cpu);
    	$.plot($("#flot-placeholder_error"), dataset_error, options_error);
    	$.plot($("#flot-placeholder_pakets"), dataset_pakets, options_pakets);


	if ( (_data.status_detect == "1")) {

		// alert('test tango termine');
		$( "#infos-results" ).html("Test Tango termin&eacute;.");
      		VarStop = 0;
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:350,
				modal: true,
				buttons: {
					"Enreg. PDF": function() {
					$( this ).dialog( "close" );
					nom_client = encodeURIComponent($( "#nom_client" ).val());
					lieu = encodeURIComponent($( "#lieu" ).val());
					nom_tech = encodeURIComponent($( "#nom_tech" ).val());
					ip_src = "<?=$ip_src?>";
					ip_dst = "<?=$ip?>";
					debit = "<?=$debit?>"; 
					paquet = "<?=$pktsize?>" ;
					tpc = "<?=$duree?>";
					tos = "<?=$tos?>";
					nego = "<?=$nego?>" ;

					generate_pdf(nom_client, lieu, nom_tech, ip_src, ip_dst, debit, paquet, tpc, tos, nego);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
					top.location.replace("/");
				}
			}
		});
	}



    	setTimeout(GetData, updateInterval);
} else {
        setTimeout(GetData, updateInterval);
}

} // Fin fonction


$(document).ready(function () {
    initData();

    dataset_debit = [        
        { label: "Data Rx", data: DataRx, lines:{fill:true, lineWidth:1.2}, color: "#00FF00" },
        { label: "Data Tx", data: DataTx, lines: { lineWidth: 1.2}, color: "#FF0000" }
    ];

    dataset_cpu = [        
        { label: "CPU", data: DataCpu, lines:{fill:true, lineWidth:1.2}, color: "#00FF00" }
    ];

    dataset_error = [        
        { label: "crc", data: Dataxrx_crc_errors, lines:{fill:true, lineWidth:1.2}, color: "#111111" },
{ label: "rx_errors", data: Dataxrx_errors, lines:{fill:true, lineWidth:1.2}, color: "#3A8EBA" },
{ label: "tx_errors", data: Dataxtx_errors, lines:{fill:true, lineWidth:1.2}, color: "#ED0000" },
{ label: "collisions", data: Dataxcollisions, lines:{fill:true, lineWidth:1.2}, color: "#FD3F92" },
{ label: "rx_dropped", data: Dataxrx_dropped, lines:{fill:true, lineWidth:1.2}, color: "#FDEE00" },
{ label: "rx_fifo_errors", data: Dataxrx_fifo_errors, lines:{fill:true, lineWidth:1.2}, color: "#00FF00" },
{ label: "tx_dropped", data: Dataxtx_dropped, lines:{fill:true, lineWidth:1.2}, color: "#FF7F00" },
{ label: "tx_fifo_errors", data: Dataxtx_fifo_errors, lines:{fill:true, lineWidth:1.2}, color: "#9E9E9E" }
    ];

  dataset_pakets = [        
        { label: "Paquets Rx", data: DataTrameRx, lines:{fill:true, lineWidth:1.2}, color: "#00FF00" },
        { label: "Paquets Tx", data: DataTrameTx, lines: { lineWidth: 1.2}, color: "#FF0000" }
    ];

    $.plot($("#flot-placeholder_debit"), dataset_debit, options_debit);
    $.plot($("#flot-placeholder_cpu"), dataset_cpu, options_cpu);
    $.plot($("#flot-placeholder_error"), dataset_error, options_error);
    $.plot($("#flot-placeholder_pakets"), dataset_pakets, options_pakets);
    setTimeout(GetData, updateInterval);
});

</script>
    </head>

    <body>
 	<table style="width:100%;">
	<tr>
		<td colspan=2>
			<center><strong>Test IP / TANGO (N3)</strong></center>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Informations:</strong>
			<pre>
<?php
	echo $str_tango_infos;
?>
<center><i>P&eacute;riode de raffraichissement : 1s. </i></center>
			</pre>
			<p id="infos-results">
			</p>
		</td>
		<td>
 			<div style="width:100%;height:1%;text-align: center;">
<!--				<input type="button" value="Stop" onclick="FonctionStop()"> -->
				<input type="button" value="Start" onclick="FonctionStart()">
				<input type="button" value="Reset" onclick="location.reload()">
<?php
	if ($mode == "testeur") {
?>
				<button id="arret" name="arret" onClick="javascript:stop_tango();">Arreter le test</button>
<?php
	 } 
?>
			</div>
		</td>
	</tr>
	</table>
	<table style="width:100%;height:25%">
		<tr>
			<td>
    				<div id="flot-placeholder_cpu" style="width:100%;height:100%;"></div>
			</td>
			<td>
    				<div id="flot-placeholder_pakets" style="width:100%;height:100%;"></div>
			</td>
		</tr>
	</table>
		<div id="flot-placeholder_debit" style="postion:relative;width:100%;height:25%;"></div>
		<div id="flot-placeholder_error" style="position:relative;width:100%;height:25%;"></div>

		<div id="dialog-confirm" title="-- R&eacute;sultats --" style="display: none;">
		<p>
			<table>
				<tr>
					<td>Client : </td>
					<td><input type=text id="nom_client" name="nom_client"></input></td>
				</tr>
				<tr>
					<td>Lieu : </td>
					<td><input type=text id="lieu" name="lieu"></input></td>
				</tr>
				<tr>
					<td>Tech. : </td>
					<td><input type=text id="nom_tech" name="nom_tech"></input></td>
				</tr>

			</table>
		</p>
</div>

    </body>
</html>
