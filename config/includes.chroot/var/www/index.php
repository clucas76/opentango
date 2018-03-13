<html>
<head> 
	<meta charset="utf-8">
	<title>INDEX</title>
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/jquery-ui/jquery-ui.js"></script>
	<script src="js/jquery.input-ip-address-control-1.0.min.js"></script>
	<script src="js/jquery.maskedinput.min.js"></script>
	<script src="js/base64.js"></script>
	<script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>

<script>
$(function() {
	$( "#dialog" ).dialog({
		autoOpen: false,
		modal: true,
		show: {
			effect: "blind",
			duration: 500
		},
		hide: {
			effect: "explode",
			duration: 500
		},
		open: function(event, ui) {
			
		}
	});
	
	var myDelOptionsVlan = {
        	// because I use "local" data I don't want to send the changes
       		// to the server so I use "processing:true" setting and delete
        	// the row manually in onclickSubmit
        	onclickSubmit: function(options, rowid) {
            		var grid_id = $.jgrid.jqID($( "#tvlan" )[0].id),
                	grid_p = $( "#tvlan" )[0].p,
                	newPage = grid_p.page;

            		// reset the value of processing option which could be modified
            		options.processing = true;

            		// delete the row
//			console.log(rowid);	
            		$( "#tvlan" ).delRowData(rowid);
			$.ajax({
  				url: 'backend/vlan.php',
  				type: 'POST',
				data : 'oper=del&id=' + rowid,
				dataType: "text",
  				success: function(data, status, xr) {
					$( "#troute" ).trigger("reloadGrid");
					$( "#tip" ).trigger("reloadGrid");
					$( "#tvlan" ).trigger("reloadGrid");
					refresh_select_if();
  				},
  				error: function(e) {
					//called when there is an error
					//console.log(e.message);
				}
			});
			// console.log("oper=del&id=" + rowid);

            		$.jgrid.hideModal("#delmod"+grid_id,
                              {gb:"#gbox_"+grid_id,
                              jqm:options.jqModal,onClose:options.onClose});

            		if (grid_p.lastpage > 1) {// on the multipage grid reload the grid
                		if (grid_p.reccount === 0 && newPage === grid_p.lastpage) {
                    			// if after deliting there are no rows on the current page
                    			// which is the last page of the grid
                    			newPage--; // go to the previous page
                		}
                		// reload grid to make the row from the next page visable.
       				$( "#tvlan" ).trigger("reloadGrid", [{page:newPage}]);
            		}
            		return true;
        	},
        	processing:true
    	}; // fin variable



	$("#tvlan").jqGrid({
      		url: "backend/vlan.php",
        	datatype: "xml",
        	mtype: "GET",
        	colNames: [" ", "VLAN ID", "Nom interface"],
        	colModel: [
			{ name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions', formatoptions:{editbutton: false, onedit:null, delbutton:true, delOptions: myDelOptionsVlan}},
            		{ name: "vlan_id", width: 125, align: "center" },
			{ name: "intf_name", width: 150, align: "center" },
        	],
        	pager: "#divvlan",
        	rowNum: 10,
        	sortorder: "desc",
        	viewrecords: true,
        	gridview: true,
		hidegrid: false,
        	autoencode: true,
		height: 'auto',
		editurl: "backend/vlan.php",
        	caption: "Affectation VLAN / Intf"
    	}); 


	var myDelOptions = {
        	// because I use "local" data I don't want to send the changes
       		// to the server so I use "processing:true" setting and delete
        	// the row manually in onclickSubmit
        	onclickSubmit: function(options, rowid) {
            		var grid_id = $.jgrid.jqID($( "#tip" )[0].id),
                	grid_p = $( "#tip" )[0].p,
                	newPage = grid_p.page;

            		// reset the value of processing option which could be modified
            		options.processing = true;

            		// delete the row
//			console.log(rowid);	
            		$( "#tip" ).delRowData(rowid);
			$.ajax({
  				url: 'backend/ip.php',
  				type: 'POST',
				data : 'oper=del&id=' + rowid,
				dataType: "text",
  				success: function(data, status, xr) {
					$( "#troute" ).trigger("reloadGrid");
					$( "#tip" ).trigger("reloadGrid");
  				},
  				error: function(e) {
					//called when there is an error
					//console.log(e.message);
				}
			});
			// console.log("oper=del&id=" + rowid);

            		$.jgrid.hideModal("#delmod"+grid_id,
                              {gb:"#gbox_"+grid_id,
                              jqm:options.jqModal,onClose:options.onClose});

            		if (grid_p.lastpage > 1) {// on the multipage grid reload the grid
                		if (grid_p.reccount === 0 && newPage === grid_p.lastpage) {
                    			// if after deliting there are no rows on the current page
                    			// which is the last page of the grid
                    			newPage--; // go to the previous page
                		}
                		// reload grid to make the row from the next page visable.
       				$( "#tip" ).trigger("reloadGrid", [{page:newPage}]);
            		}
		
            		return true;
        	},
        	processing:true
    	}; // fin variable

	var myDelOptionsFlux = {
        	// because I use "local" data I don't want to send the changes
       		// to the server so I use "processing:true" setting and delete
        	// the row manually in onclickSubmit
        	onclickSubmit: function(options, rowid) {
            		var grid_id = $.jgrid.jqID($( "#tflux" )[0].id),
                	grid_p = $( "#tflux" )[0].p,
                	newPage = grid_p.page;
			var id_db = $( '#tflux' ).jqGrid ('getCell', rowid, 1);

            		// reset the value of processing option which could be modified
            		options.processing = true;

            		// delete the row
//			console.log(rowid);	
            		$( "#tflux" ).delRowData(id_db);
			$.ajax({
  				url: 'backend/flux.php',
  				type: 'GET',
				data : 'cmd=del&id=' + id_db,
				dataType: "text",
  				success: function(data, status, xr) {
					$( "#tflux" ).trigger("reloadGrid");
  				},
  				error: function(e) {
					//called when there is an error
					//console.log(e.message);
				}
			});
			// console.log("oper=del&id=" + rowid);

            		$.jgrid.hideModal("#delmod"+grid_id,
                              {gb:"#gbox_"+grid_id,
                              jqm:options.jqModal,onClose:options.onClose});

            		if (grid_p.lastpage > 1) {// on the multipage grid reload the grid
                		if (grid_p.reccount === 0 && newPage === grid_p.lastpage) {
                    			// if after deliting there are no rows on the current page
                    			// which is the last page of the grid
                    			newPage--; // go to the previous page
                		}
                		// reload grid to make the row from the next page visable.
       				$( "#tflux" ).trigger("reloadGrid", [{page:newPage}]);
            		}
		
            		return true;
        	},
        	processing:true
    	}; // fin variable

	// Grilles !!!!

	$("#tip").jqGrid({
      		url: "backend/ip.php",
        	datatype: "xml",
        	mtype: "GET",
        	colNames: [" ", "Prefix", "Longueur", "Device"],
        	colModel: [
			{ name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions', formatoptions:{editbutton: false, onedit:null, delbutton:true, delOptions: myDelOptions}},
            		{ name: "prefix", width: 125, align: "center" },
			{ name: "longueur", width: 100, align: "center" },
            		{ name: "device", width: 75, align: "center" }
        	],
        	pager: "#divip",
        	rowNum: 10,
        	sortorder: "desc",
        	viewrecords: true,
        	gridview: true,
		hidegrid: false,
        	autoencode: true,
		height: 'auto',
		editurl: "backend/ip.php",
        	caption: "Affectation IP / Intf"
    	}); 

	$("#tflux").jqGrid({
      		url: "backend/flux.php",
        	datatype: "xml",
        	mtype: "GET",
        	colNames: [" ", "Nom", "TOS", "Debit", "Pkt_Size", "IP_SRC", "IP_DST"],
        	colModel: [
			{ name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions', formatoptions:{editbutton: false, onedit:null, delbutton:true, delOptions: myDelOptionsFlux}},
            		{ name: "nom", width: 50, align: "center" },
			{ name: "tos", width: 50, align: "center" },
			{ name: "debit", width: 50, align: "center" },
			{ name: "pkt_size", width: 75, align: "center" },
			{ name: "ip_src", width: 125, align: "center" },
			{ name: "ip_dst", width: 125, align: "center" },
            	
        	],
        	pager: "#divflux",
        	rowNum: 10,
        	sortorder: "desc",
        	viewrecords: true,
        	gridview: true,
		hidegrid: false,
        	autoencode: true,
		height: 'auto',
		editurl: "backend/flux.php",
        	caption: "Flows"
    	}); 

	
	$("#troute").jqGrid({
      		url: "backend/route.php",
        	datatype: "xml",
        	mtype: "GET",
        	colNames: [" ", "Prefix", "Longueur", "Gateway", "Device"],
        	colModel: [
			{ name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions', formatoptions:{editbutton: false, onedit:null, delbutton:true}},
            		{ name: "prefix", width: 125, align: "center" },
			{ name: "longueur", width: 100, align: "center" },
			{ name: "gateway", width: 125, align: "center" },
            		{ name: "device", width: 75, align: "center" }
        	],
        	pager: "#divroute",
        	rowNum: 10,
        	sortorder: "desc",
        	viewrecords: true,
        	gridview: true,
		hidegrid: false,
        	autoencode: true,
		height: 'auto',
		editurl: "backend/route.php",
        	caption: "Table de routage"
    	}); 




	$( "#accordion" ).accordion({
		collapsible: true, 
		active: false,
		heightStyle: "content",
		activate: function( event, ui ) {
			if (ui.newHeader.text() == "Test IP / TANGO (N3)") {
				$.getJSON("./backend/get_ip.php",function(j){
      					var options = '';
      					for (var i = 0; i < j.length; i++) {
        					options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
      					}
					$( "#select_ip_src" ).html(options);
				});
			} else if (ui.newHeader.text() == "Test QOS") {
				$.getJSON("./backend/get_ip.php",function(j){
      					var options = '';
      					for (var i = 0; i < j.length; i++) {
        					options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
      					}
					$( "#flux_ip_src" ).html(options);
				});
			} else if (ui.newHeader.text() == "Configurations") {
				refresh_select_if();
			}
		}
	});
	$( "#check" ).button();
	$( "input[type=submit], a, button" )
		.button()
		.click(function( event ) {
		event.preventDefault();
	});
	$( "#radio_mode" ).buttonset();
	$( '#ipv4' ).ipAddress();
	$( '#ipv4_subnet' ).ipAddress();
	$( '#ipv4_gateway' ).ipAddress();
	$( "#ipv4_subnetcidr" ).mask("99");
//	$( "#vlan_id" ).mask("9999");
	$( '#ipv4_target' ).ipAddress();
	$( "#pktsize" ).buttonset();
//	$( "#duree" ).mask("999");
//	$( "#debit" ).mask("9*999");
	$( "#test" ).click(function() {
		$( "#dialog_p").html("Test en cours, veuillez patienter...");
		$( "#dialog" ).dialog( "open" );	
		$.ajax({
  			url: 'ping.php',
  			type: 'GET',
			data : 'ip=' + $( "#ipv4_target" ).val(),
			dataType: "text",
  			success: function(data, status, xr) {
				$( "#dialog_p" ).html(data);
  			},
  			error: function(e) {
				//called when there is an error
				//console.log(e.message);
  			}
		});
	});
	$( "#add_flux" ).click(function() {
		add_flux();
	});
	$( "#radio_mode" ).click(function() {
		var e = $( "#radio_mode" ).val();
		alert(e);
	});
});

var speed = 0;

function check_bwtango()
{
	var my_mode = $( "#bwtango input[type='radio']:checked").val();
	if (my_mode != "boucle" ) {
		var ip = $( "#ipv4_target" ).val(); 
		if ( ip == "___.___.___.___" ) {
			alert("Pas une IPV4");
			return ;
		}

		var debit = $( "#debit" ).val();
		if ( debit == "_____" ) {
			alert("Merci de preciser un debit correct.");
			return ;
		}
	}
	var str = $( "#nego_result" ).html();
	var str_arr = str.split("-");
	var str_to_submit = str_arr[0] + " -- " + str_arr[2];
	
	document.getElementById("nego_hidden").value = str_to_submit;
	document.forms['bwtango'].submit();
}

function check_qos()
{
	var data = $("#tflux").jqGrid('getRowData');
	str = JSON.stringify(data);
	var str2 = Base64.encode(str);
	document.getElementById("qos_tab").value = str2;
	document.forms['bwqos'].submit();
}



function get_infos()
{
	$.ajax({
  		url: 'get_infos.php',
  		type: 'GET',
		dataType: "text",
  		success: function(data, status, xr) {
		//called when successful
			$('#infos-results').html(data);
  		},
		complete: function () {
	//		setTimeout(get_infos, 2000);
		},	
  		error: function(e) {
			//called when there is an error
			//console.log(e.message);
  		}
	});
}

function nego()
{
	var nego = $( "#nego" ).val();
	var auto = $( "#autoneg" ).is(':checked');
	$.ajax({
  		url: 'nego.php',
  		type: 'POST',
		dataType: "text",
		data: "nego=" + nego + "&auto=" + auto,
  		success: function(data, status, xr) {
		//called when successful
		//	$('#infos-results').html(data);
  		},
		complete: function () {
		},	
  		error: function(e) {
			//called when there is an error
			//console.log(e.message);
  		}
	});

}

function ip_auto()
{
	var ip_auto = $( "#ip_auto" ).val();
	$.ajax({
  		url: 'ip_auto.php',
  		type: 'POST',
		dataType: "text",
		data: "ip_auto=" + ip_auto,
  		success: function(data, status, xr) {
	  		},
		complete: function () {
			//called when successful
			setTimeout(function(){ 
				$( "#troute" ).trigger("reloadGrid");
				$( "#tip" ).trigger("reloadGrid");
			}, 2000);
			},	
  		error: function(e) {
			//called when there is an error
			//console.log(e.message);
  		}
	});
}


function submit_route()
{
	var ip 		= $( "#ipv4_subnet" ).val();
	var cidr	= $( "#ipv4_subnetcidr" ).val();
	var gateway 	= $( "#ipv4_gateway" ).val(); 

        $.ajax({
                url: 'route.php',
                type: 'POST',
                dataType: "text",
                data: "action=add&route=" + ip + "&cidr=" + cidr + "&gateway=" + gateway,
                success: function(data, status, xr) {
                //called when successful
                //      $('#infos-results').html(data);
			$( "#troute" ).trigger("reloadGrid");
                },
                complete: function () {
                },
                error: function(e) {
                        //called when there is an error
                        //console.log(e.message);
                }
        });

}

function add_ip()
{
	var ipv4 	= $( "#ipv4" ).val();
	var netmask	= $( "#netmask" ).val();
	var intf 	= $( "#select_if" ).val(); 

        $.ajax({
                url: 'ip.php',
                type: 'POST',
                dataType: "text",
                data: "action=add&ip=" + ipv4 + "&netmask=" + netmask + "&intf=" + intf,
                success: function(data, status, xr) {
			// On reload la table de routage
			$( "#troute" ).trigger("reloadGrid");
			$( "#tip" ).trigger("reloadGrid");
                },
                complete: function () {
                },
                error: function(e) {
                        //called when there is an error
                        //console.log(e.message);
                }
        });
}

function add_vlan()
{
	var vlan = $( "#vlan_id" ).val();
	if ( isNaN(vlan) == true) {
		alert("Merci de specifier un nombre!");
	} else if ( (vlan < 1) || (vlan > 4096)) {
		alert("Merci de specifier un entier entre 1 et 4096 inclus!");
	} else {
      	  	$.ajax({
       	        	url: 'vlan.php',
       	         	type: 'POST',
       	         	dataType: "text",
                	data: "action=add&vlan_id=" + vlan,
                	success: function(data, status, xr) {
				// On reload la table de routage
				$( "#tvlan" ).trigger("reloadGrid");
                	},
                	complete: function () {
                	},
                	error: function(e) {
                        	//called when there is an error
                        	//console.log(e.message);
                	}
        	});
		refresh_select_if();
	}
}

function add_flux()
{
	var ip_src 	= $( "#flux_ip_src" ).val();
	var ip_dst 	= $( "#flux_ip_dst" ).val();
	var debit	= $( "#flux_debit" ).val();
	var tos 	= $( "#flux_tos" ).val(); 
	var pkt_size 	= $( "#flux_pkt_size" ).val(); 
//	var nom		= $( "#" ).val();

        $.ajax({
                url: './backend/flux.php',
                type: 'GET',
                dataType: "text",
                data: "cmd=add&ip_src=" + ip_src + "&ip_dst=" + ip_dst + "&debit=" + debit + "&tos=" + tos + "&pkt_size=" + pkt_size,
                success: function(data, status, xr) {
			// On reload la table des flux
			$( "#tflux" ).trigger("reloadGrid");
                },
                complete: function () {
                },
                error: function(e) {
                        //called when there is an error
                        //console.log(e.message);
                }
        });
}



function refresh_select_if()
{
	$.getJSON("./backend/get_if.php",function(j){
      		var options = '';
      		for (var i = 0; i < j.length; i++) {
       			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
      		}
		$( "#select_if" ).html(options);
	});
}


function Refresh_Config_Data()
{
  	$.ajax({
        	url: 'backend/get_nego.php',
          	type: 'POST',
          	dataType: "text",
            // 	data: "action=add&vlan_id=" + vlan,
              	success: function(data, status, xr) {
			// On reload la table de routage
			var d = data.split(':');
			var r = d[0] + " " + d[1] + " Duplex -- " ;
			if (d[2] == "on") 
				r = r + "Autoneg : on -- ";
			else
				r = r + "Autoneg : off -- "; 
		
			if (d[3] == "1")
				r = r + "Lien Ethernet : <font color=green>connect&eacute;</font>";
			else
				r = r + "Lien Ethernet : <font color=red>cable d&eacute;branch&eacute;</font>";
			$( "#nego_result" ).html(r);	
			var r_ip = "Configuration type IP : " + d[4];
			$( "#type_ip_result" ).html(r_ip);	
              	},
              	complete: function () {
               	},
               	error: function(e) {
                       	//called when there is an error
                       	//console.log(e.message);
               	}
       	});
	setTimeout(Refresh_Config_Data, 1000);
}

setTimeout(Refresh_Config_Data, 1000);


</script>
</head>
<body>
<div id="dialog" title="Mode testeur : test cible">
<p id="dialog_p">Test en cours... Veuillez patienter.</p>
</div>
<center><img src="./img/logo.png" width=250px border=0 /></center>
<div id="accordion" style="height: 300px;">
<h3>Configurations</h3>
<div>
<p>
<fieldset>
    <legend>N&eacute;gociation ethernet :</legend>
	<select id="nego" name="nego">>
		<option value="1000-full">1000baseTx-FD</option>
		<option value="1000-half">1000baseTx-HD</option>
		<option value="100-full" selected>100baseTx-FD</option>
		<option value="100-half">100baseTx-HD</option>
		<option value="10-full">10baseT-FD</option>
		<option value="10-half">10baseT-HD</option>
	</select>
	<input type="checkbox" id="autoneg" name="autoneg"><label for="check">Auton&eacute;gociation</label>
	<input type="submit" value="VALIDER" onClick="javascript:nego();"><br />
	<span id="nego_result" name="nego_result"></span>
</fieldset>
<fieldset>
    <legend>IP automatique / statique :</legend>
	<select id="ip_auto" name="nego">>
		<option value="static">Statique</option>
		<option value="auto">Automatique</option>
	</select>
	<input type="submit" value="VALIDER" onClick="javascript:ip_auto();"><br />
	<span id="type_ip_result" name="type_ip_result"></span>
</fieldset>

<fieldset>
    <legend>VLAN :</legend>
	<input type=text id="vlan_id"></input>
	<input type="submit" value="VALIDER" onClick="javascript:add_vlan();">
	<table id="tvlan"></table>
	<div id="divvlan"></div>

</fieldset>
<fieldset>
    <legend>IP :</legend>
	<table>
		<tr>
			<td>IP : <input type=text id="ipv4"></input>
			Netmask</td><td colspan=4><select name="netmask" id="netmask">
				<option value="24">/24 (255.255.255.0)</option>
				<option value="25">/25 (255.255.255.128)</option>
				<option value="26">/26 (255.255.255.192)</option>
				<option value="27">/27 (255.255.255.224)</option>
				<option value="28">/28 (255.255.255.240)</option>
				<option value="29">/29 (255.255.255.248)</option>
				<option value="30">/30 (255.255.255.252)</option>
				<option value="31">/31 (255.255.255.254)</option>
			</select>
			<select name="select_if" id="select_if"></select>
			<input type="submit" value="VALIDER" onClick="javascript:add_ip();">
		</tr>
	</table>
	<table id="tip"></table>
	<div id="divip"></div>
</fieldset>
<fieldset>
    <legend>Route :</legend>
	<table>
		<tr></td>Subnet:</td><td><input type=text id="ipv4_subnet"></input>/<input type=text id="ipv4_subnetcidr" /></td><td>Via : </td><td><input type=test id="ipv4_gateway"></input></td><td><input type="submit" value="VALIDER" onClick="javascript:submit_route();"></td></tr>
	</table>
	<table id="troute"></table>
	<div id="divroute"></div>
</fields>
</p>
</div>

<h3>Test Ethernet (N2)</h3>
<div>
<p>
Ce test sera impl&eacute;ment&eacute; dans la version 2.0.
</p>
</div>

<h3>Test IP / TANGO (N3)</h3>
<div>
<p>
<fieldset>
    <legend>MODE :</legend>
	 <div id="radio">
		<form name="bwtango" id="bwtango" method="POST" action="tango.php">
		<table>
			<tr>
			<td>
				<input type="radio" id="radio_mode1" name="radio_mode" value="boucle"><label for="radio1_mode">Boucle</label></input>
				<input type="radio" id="radio_mode2" name="radio_mode" value="testeur" checked="checked"><label for="radio2_mode">Testeur</label></input>
			</td>
			</tr>
			<tr><td>IP src :</td><td><select name="select_ip_src" id="select_ip_src"></select></td></tr>
			<tr>
				<td>IP cible : </td>
				<td><input type=text id="ipv4_target" name="ipv4_target" value="194.158.102.114"></input><button id="test" value="test">Test</button></td>
			</tr>
			<tr><td>D&eacute;bit (kbit/s) : </td><td><input type=text id="debit" name="debit" value=95000></input></td></tr>
			<tr><td>Dur&eacute;e par cycle (s): </td>
				<td>
					<select id="duree" name="duree">
						<option value="120">2 minutes</option>
						<option value="180">3 minutes</option>
						<option value="240">4 minutes</option>
						<option value="300" selected>5 minutes</option>
						<option value="600">10 minutes</option>
						<option value="900">15 minutes</option>
					</select>
					<!-- <input type=text id="duree" name="duree"></input> -->
				</td>
			</tr>
			<tr>
				<td>TOS</td>
				<td>
					<select id="tos" name="tos">
						<option value="0">Default</option>
						<option value="32">CS1</option>
						<option value="40">AF11</option>
						<option value="48">AF12</option>
						<option value="64">CS2</option>
						<option value="72">AF21</option>
						<option value="80">AF22</option>
						<option value="96">CS3</option>
						<option value="104">AF31</option>
						<option value="112">AF32</option>
						<option value="128">CS4</option>
						<option value="136">AF41</option>
						<option value="144">AF42</option>
						<option value="184">EF</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
				Taille de paquets :
				<div id="pktsize">
					<input type="checkbox" id="check1" name="pktsize[]" value=64><label for="check1">64</label>
					<input type="checkbox" id="check2" name="pktsize[]" value=128><label for="check2">128</label>
					<input type="checkbox" id="check3" name="pktsize[]" value=256><label for="check3">256</label>
					<input type="checkbox" id="check4" name="pktsize[]" value=512><label for="check4">512</label>
					<input type="checkbox" id="check5" name="pktsize[]" value=1024><label for="check5">1024</label>
					<input type="checkbox" id="check6" name="pktsize[]" value=1280 checked><label for="check6">1280</label>
					<input type="checkbox" id="check7" name="pktsize[]" value=1500 checked><label for="check7">1500</label>
				</div>	
				</td>
			</tr>
		</table>
	</div>
</fieldset>
	<input type="hidden" id="nego_hidden" name="nego_hidden" />
	</form>
	<center><button id="bwtango" value="bwtango" onClick="javascript:check_bwtango();"> Lancer le test </button>
</p>
</div>

<h3>Test TCP / UDP (N4)</h3>
<div>
<p>
Ce test sera impl&eacute;ment&eacute; dans la version 2.0.
</p>
</div>


<h3>Test QOS</h3>
<div>
<p>
	<form name="bwqos" id="bwqos" method="POST" action="qos.php">

<fieldset>
    <legend>CONFIGURATION FLUX :</legend>
	<table>	
			<tr><td>Dur&eacute;e par cycle (s): </td>
				<td>
					<select id="duree" name="duree">
						<option value="120">2 minutes</option>
						<option value="180">3 minutes</option>
						<option value="240">4 minutes</option>
						<option value="300" selected>5 minutes</option>
						<option value="600">10 minutes</option>
						<option value="900">15 minutes</option>
					</select>
					<!-- <input type=text id="duree" name="duree"></input> -->
				</td>
			</tr>
	</table>
</fieldset>

<fieldset>
    <legend>CONFIGURATION FLUX :</legend>
	 <div id="radio">
		<table>
			<tr>
			<td>
				TOS :
				<select id="flux_tos" name="flux_tos">
					<option value="0">0</option>
					<option value="32">CS1</option>
					<option value="40">AF11</option>
					<option value="48">AF12</option>
					<option value="64">CS2</option>
					<option value="72">AF21</option>
					<option value="80">AF22</option>
					<option value="96">CS3</option>
					<option value="104">AF31</option>
					<option value="112">AF32</option>
					<option value="128">CS4</option>
					<option value="136">AF41</option>
					<option value="144">AF42</option>
					<option value="184">EF</option>
				</select>
				Debit <input type=text name=flux_debit id=flux_debit size=5 ></input>
				pkt size : 
				<select id="flux_pkt_size" name="flux_pkt_size">
					<option value="64">64</option>
					<option value="128">128</option>
					<option value="256">256</option>
					<option value="512">512</option>
					<option value="1024">1024</option>
					<option value="1280">1280</option>
					<option value="1500">1500</option>
				</select>
				IP src : <select name="flux_ip_src" id="flux_ip_src"></select>
				IP dst : 
				<input type=text id="flux_ip_dst" name="flux_ip_dst" value="194.158.102.114" size=15></input>
				<button id="add_flux" value="add_flux">Add</button>
			</td>
			</tr>
		</table>
		<table id="tflux"></table>
		<div id="divflux"></div>
	</div>
</fieldset>
	<input type=hidden id=qos_tab name=qos_tab />
	</form>	
	<center><button id="qos" value="qos" onClick="javascript:check_qos();"> Lancer le test </button>

</p>
</div>

</div>
</body>
</html>
