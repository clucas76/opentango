<?php

include_once('comm.inc.php');
include_once('qos.inc.php');

$duree 	= $_POST["duree"];
$tab 	= $_POST["qos_tab"];

$json_tab = base64_decode($tab);

$cmd = "qos:start:" . $duree . ":" . $tab;
send_to_tangod($cmd);

$test = nbflux_php();
//echo $test;

function getColor($num) {
    $hash = '649B88FC5D5DBA9B61FF69B4FBF2B7FFFF001FFED80000000000FF00FF00FF0000'; // liste de couleur
        $i= 6*$num;
	$r = substr($hash, -$i,6); 
$color = "#$r";
return $color;
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
function stop_qos()
{
        $.ajax({
                url: 'stop_qos.php',
                type: 'GET',
                dataType: "text",
                success: function(data, status, xr) {
			// status:test_nr:nr:pktsize:bandwidth:duree:
				$( "#infos-results" ).html(" annul&eacute;.");
				VarStop = 0;
				top.location.replace("/");
                },
                error: function(e) {
                        //called when there is an error
                        //console.log(e.message);
                }
        });
}


function generate_pdf(nom_client, lieu, nom_tech, tpc, tab)
{
        $.ajax({
                url: './PDF/generationPdfQOS.php',
                type: 'GET',
                dataType: "text",
		data: "nom_client="+ nom_client + "&lieu=" + lieu + "&nom_tech=" + nom_tech + "&tpc=" + tpc + "&tab=" + tab,
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

var VarStop = 1;

function FonctionStart()
{
	VarStop = 1;
}

function FonctionStop()
{
      	VarStop = 0;
}

<?php
//echo "var tab =" . $json_tab . ";";
//echo "alert(tab);";
?>

//récupére la table JSON
var tab = <?php echo $json_tab ?> ;
//document.write("<p>la table JSON: "+tab+"</p></br>");

//récupére la durée
var duree = <?php echo $duree;?>;
//document.write("<p>la duree: "+duree+"</p></br>");

//calcul du nombre de flux 
var nbflux = tab.length;
//document.write("<p>nbflux : "+nbflux +"</p></br>");

//lire dans un tableau Json en 2d

	var tab_inputs = "<table style=\"border: 1px solid black; border-collapse: collapse; text-align:center;\"><tr><td style=\"border: 1px solid black;  background-color:silver;\">Flux</td><td style=\"border: 1px solid black; background-color:silver;\">TOS</td><td style=\"border: 1px solid black; background-color:silver;\">D&eacute;bit</td><td style=\"border: 1px solid black;  background-color:silver;\">Pkt_Size</td><td style=\"border: 1px solid black; background-color:silver;\">IP_SRC</td><td style=\"border: 1px solid black; background-color:silver;\">IP_DST</td></tr>";

	for (var i=0; i<nbflux; i++){
		n = i + 1;
		var str_test_QOS_infos = "<tr style=\"border: 1px solid black;\"><td style=\"border: 1px solid black;\">" + n + "</td><td style=\"border: 1px solid black;\">" + tab[i]["tos"] + "</td><td style=\"border: 1px solid black;\">" + tab[i]["debit"] + "</td><td style=\"border: 1px solid black;\">" + tab[i]["pkt_size"] + "</td><td style=\"border: 1px solid black;\">" + tab[i]["ip_src"] + "</td><td style=\"border: 1px solid black;\">" + tab[i]["ip_dst"]+"</td></tr>";
		tab_inputs += str_test_QOS_infos; 
	}
	tab_inputs += "</table>";

// déclaratione des variables
<?php
$nbflux = nbflux_php();
for ($i=1; $i<=$nbflux; $i++){
echo "var debit_flux_".$i."= [],latence_flux_".$i."= [],perte_flux_".$i."= [];";			
//echo "alert(declaration_tableau);";
}
?>

var tab_cpu = "0";
var tab_rx_packets = "0";
var tab_tx_packets = "0" ;

 

var dataset_debit,dataset_latence,dataset_perte;
var min = <?php echo $duree;?>;
var totalPoints = (min*60)/8;
var updateInterval = 1000;
var now = new Date().getTime();

var options_debit = {
    series: {
        lines: {
            	lineWidth: 1.2, 
		show : true
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
            axisLabel: "debit",
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

var options_latence = {
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
            axisLabel: "latence",
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


var options_perte = {
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
            axisLabel: "Drop",
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

 		<?php 
		$nbflux = nbflux_php();
		for ($i=1; $i<=$nbflux; $i++){
		echo  'debit_flux_'.$i.'.push(temp);';
		echo  'latence_flux_'.$i.'.push(temp);';
		echo  'perte_flux_'.$i.'.push(temp);';
		}
		?>
		
    	}
}

function GetData() 
{
    	$.ajaxSetup({ cache: false });

    	$.ajax({
        	url: "./backend/data_qos.php?nbflux=" + nbflux,
       	 	dataType: 'json',
		type : 'GET',
        	success: update,
        	error: function () {
            		setTimeout(GetData, updateInterval);
        	}
    	});
}

var temp;

function update(_data) {

	if (VarStop == 1) {

  var now = new Date().getTime();

	<?php 
	$nbflux = nbflux_php();
	for ($i=1; $i<=$nbflux; $i++){
		echo  'debit_flux_'.$i.'.shift();';
		echo  'latence_flux_'.$i.'.shift();';
		echo  'perte_flux_'.$i.'.shift();';

   		echo  'temp = [now, _data.debit_flux_'.$i.'];';
		echo  'debit_flux_'.$i.'.push(temp);';

    		echo  'temp = [now, _data.latence_flux_'.$i.'];';
		echo  'latence_flux_'.$i.'.push(temp);';

    		echo  'temp = [now, _data.perte_flux_'.$i.'];';
		echo  'perte_flux_'.$i.'.push(temp);';
		}

		$reponse = 'dataset_debit = [';
	for ($u=1; $u<=$nbflux; $u++){
		$coldyn = getColor($u);
		$reponse = $reponse.'{ label: "flux_'.$u.': " + _data.debit_flux_'.$u.' + "Kb/s", data: debit_flux_'.$u.', lines: { fill: true, lineWidth: 1.2 }, color: "' . $coldyn . '" },';
    	}
		//on supprime la derniére ,
		$reponse = substr($reponse, 0, -1);
		//on ferme la réponse
		$reponse = $reponse.'];';
	echo $reponse;

		$reponse = 'dataset_latence = [';		
	for ($u=1; $u<=$nbflux; $u++){
		$coldyn = getColor($u);
		$reponse = $reponse.'{ label: "flux_'.$u.': " + _data.latence_flux_'.$u.' + "ms", data: latence_flux_'.$u.', lines: { fill: true, lineWidth: 1.2 }, color: "' . $coldyn . '" },';
    	}
		//on supprime la derniére ,
		$reponse = substr($reponse, 0, -1);
		//on ferme la réponse
		$reponse = $reponse.'];';
	echo $reponse;

		$reponse = 'dataset_perte = [';
	for ($u=1; $u<=$nbflux; $u++){
		$coldyn = getColor($u);
		$reponse = $reponse.'{ label: "flux_'.$u.': " + _data.perte_flux_'.$u.' + "%", data: perte_flux_'.$u.', lines: { fill: true, lineWidth: 1.2 }, color: "' . $coldyn . '" },';
    	}
		//on supprime la derniére ,
		$reponse = substr($reponse, 0, -1);
		//on ferme la réponse
		$reponse = $reponse.'];';
	echo $reponse;
	
	?>

    	var p_debit = $.plot($("#flot-placeholder_debit"), dataset_debit, options_debit);
    	var p_latence = $.plot($("#flot-placeholder_latence"), dataset_latence, options_latence);
    	var p_perte = $.plot($("#flot-placeholder_perte"), dataset_perte, options_perte);

     tab_cpu = _data.DataCpu;
     tab_rx_packets = _data.DataTrameRx;
     tab_tx_packets = _data.DataTrameTx ;

	$( "#tab_cpu" ).html("CPU : " + tab_cpu + " %<br/>");
	$( "#tab_rx_packets" ).html("Pkt Tx : " + tab_rx_packets + " pps<br/>" );
	$( "#tab_tx_packets" ).html("Pkt Rx : " + tab_rx_packets + " pps<br/>");

     if (_data.status_detect == "1") {

	var canvasData = p_debit.getCanvas().toDataURL("image/png");
	$.ajax({

    		url:'backend/save_img.php', 
    		type:'POST', 
    		data:{
			name:'debit',
        		data:canvasData
    		},
		success: function(result) {
        		console.log(result);
    		}
	});
	var canvasData = p_latence.getCanvas().toDataURL("image/png");
	$.ajax({
    		url:'backend/save_img.php', 
    		type:'POST', 
    		data:{
			name:'latence',
        		data:canvasData
    		}
	});
	var canvasData = p_perte.getCanvas().toDataURL("image/png");
	$.ajax({
    		url:'backend/save_img.php', 
    		type:'POST', 
    		data:{
			name:'perte',
        		data:canvasData
    		}
	});


              //  alert('test tango termine');
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
                                        tpc = "<?=$duree?>";
                                        tab = "<?=$tab?>";

                                        generate_pdf(nom_client, lieu, nom_tech, tpc, tab);
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
}


$(document).ready(function () {
    initData();
	<?php 
	$nbflux = nbflux_php();

	$reponse = 'dataset_debit = [';
	for ($v=1; $v<=$nbflux; $v++){
	$coldyn = getColor($v);	
	$reponse = $reponse.'{ label: "flux_'.$v.'", data: debit_flux_'.$v.', lines:{fill:true, lineWidth:1.2}, color: "'.$coldyn.'" },';
    	}
		//on supprime la derniére ,
		$reponse = substr($reponse, 0, -1);
		//on ferme la réponse
		$reponse = $reponse.'];';
	echo $reponse;

		$reponse = 'dataset_latence = [';		
	for ($v=1; $v<=$nbflux; $v++){
		$coldyn = getColor($v);
		$reponse = $reponse.'{ label: "flux_'.$v.'", data: latence_flux_'.$v.', lines: { fill: true, lineWidth: 1.2 }, color: "'.$coldyn.'" },';
    	}
		//on supprime la derniére ,
		$reponse = substr($reponse, 0, -1);
		//on ferme la réponse
		$reponse = $reponse.'];';
	echo $reponse;

		$reponse = 'dataset_perte = [';
	for ($v=1; $v<=$nbflux; $v++){
		$coldyn = getColor($v);
		$reponse = $reponse.'{ label: "flux_'.$v.'", data: perte_flux_'.$v.', lines: { fill: true, lineWidth: 1.2 }, color: "'.$coldyn.'" },';
    	}
		//on supprime la derniére ,
		$reponse = substr($reponse, 0, -1);
		//on ferme la réponse
		$reponse = $reponse."];";
	echo $reponse;
	?>

    $.plot($("#flot-placeholder_debit"), dataset_debit, options_debit);
    $.plot($("#flot-placeholder_latence"), dataset_latence, options_latence);
    $.plot($("#flot-placeholder_perte"), dataset_perte, options_perte);

	$( "#tab_inputs" ).html("Dur&eacute;e : " + duree + " secondes<br/>" + tab_inputs);
    setTimeout(GetData, updateInterval);
});

</script>
</head>
<body>
 	<table style="width:100%;">
	<tr>
		<td colspan=5>
			<center><strong>Test QOS <p id="infos-results"></p> </strong></center>
			
		</td>
	</tr>
	<tr>
		<td>
			<strong>Informations:</strong><br/>
			<div id='tab_inputs' name='tab_inputs'></div>
			
		</td>
		<td>
			 <div style=\"border: 1px solid black; border-collapse: collapse; text-align:center;\" id='tab_cpu' name='tab_cpu'></div> 
	
		</td>
		<td>
			<div id='tab_rx_packets' name='tab_rx_packets'></div>
		</td>
		<td>
			<div id='tab_tx_packets' name='tab_rx_packets'></div>
		</td>
		<td>

			<button id="arret" name="arret" onClick="javascript:stop_qos();">Arreter le test</button>
		
		</td>
	</tr>
	<tr>
		<td colspan=5>
			<center><i>P&eacute;riode de raffraichissement : 1s. </i></center>
		</td>
	</tr>
	</table>
		<div id="flot-placeholder_debit" style="postion:relative;width:100%;height:25%;"></div>
		<div id="flot-placeholder_latence" style="position:relative;width:100%;height:25%;"></div>
		<div id="flot-placeholder_perte" style="position:relative;width:100%;height:25%;"></div>

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

