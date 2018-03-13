<?php

require('fonction.php');
include_once('/var/www/comm.inc.php');
include_once('/var/www/qos.inc.php');

$pdf = new PDF();
$titre = 'Resultat du Test QoS';
$fichier = '/tmp/qos/qos.results';

//variable par defaut
$NomTest = "Test debit QoS";
$NomClient = "One Operateur";
$Lieu = "Bois Guillaume";
$NomTech = "inconnue";
$NomTest	= "Test OpenTango QoS";
$tpc		= "120";

//variable fixe
$NomTest	= $_GET['nom_test'];
$NomClient 	= $_GET['nom_client'] ;
$Lieu		= $_GET['lieu'] ;
$today 		= date("d-m-Y-H-i-s");
$NomTech	= $_GET['nom_tech'] ;
$tpc		= $_GET['tpc'];


//variable dynamique
$tab 	= $_GET["tab"];
$json_tab = base64_decode($tab);


$json_data = json_decode($json_tab);
$cmd = "";
$i="1";
unlink('/tmp/qos/qos_tableau.txt');
$monfichier = fopen('/tmp/qos/qos_tableau.txt', 'a');		
		$cmd = "chown www-data.www-data /tmp/qos/qos_tableau.txt";
		exec($cmd);
		$ecrire = "";
        	fwrite($monfichier, $ecrire);
    //déclaration des coleurs
    $c="0";   
    $color[0] = "255,0,0";
    $color[1] = "0,255,0";
    $color[2] = "0,0,255";
    $color[3] = "0,0,0";
    $color[4] = "31,254,216";
    $color[5] = "255,255,0";
    $color[6] = "251,242,183";
    $color[7] = "255,105,180";
    $color[8] = "186,155,97";
    $color[9] = "252,93,93";
    $color[10] = "100,155,136";

	foreach($json_data as $v){
		$nom		= $i;
		$tos		= $v->tos;
		$pkt_size 	= $v->pkt_size;	
		$debit 		= $v->debit;
		$ip_src		= $v->ip_src;
		$ip_dst		= $v->ip_dst;
		$i++;
		$monfichier = fopen('/tmp/qos/qos_tableau.txt', 'a');		
		$cmd = "chown www-data.www-data /tmp/qos/qos_tableau.txt";
		exec($cmd);
		$ecrire = $nom . ":" . $color[$c] . ":" . tos_to_dscp($tos) . ":" . $pkt_size . ":" . $debit . ":" . $ip_src . ":" . $ip_dst . "\n";
        	fwrite($monfichier, $ecrire);
		$c++;
	}

$monfichier = fopen('/tmp/qos/qos_tableau.txt', 'a');		
		$cmd = "chown www-data.www-data /tmp/qos/qos_tableau.txt";
		exec($cmd);
		$ecrire = "";
        	fwrite($monfichier, $ecrire);


//non du fichier généré
$fichier_out = "Resultats-Qos-" . $NomClient . "-" . $Lieu . "-" . $today . ".pdf";


$pdf->SetTitle($titre);
$pdf->SetAuthor('OpenTango team.');




$pdf->AddPage();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->TitreChapitre(1,"Recapitulatif du test QoS");
$pdf->RecapQos($fichier,$NomClient,$Lieu,$NomTest,$NomTech,$tpc);
$pdf->Cell(10,6,"La commande : ",0,0,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$header = array('Flux','Color', 'Tos', 'Pkt_size', 'Debit','IP_SRC','IP_DST' );
$data = $pdf->LoadData('/tmp/qos/qos_tableau.txt');
$pdf->FancyTable($header,$data);





$pdf->AddPage();
$pdf->Ln();
$pdf->Ln();
$pdf->TitreChapitre(2,"Les resultats sur le debit");
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$nbflux = count(json_decode($json_tab));
$data_debit = array();
	for ($i=0; $i<$nbflux; $i++)
	{
		$data_debit[$i][0] = $i;
		$data_debit[$i][1] = exec ( "sed -n ''p /tmp/qos/flow-" . $i . "/tango.results'' | tail -1 | tr -s ' ' ' ' | cut -d  ' ' -f 13")*100 ;
		$data_debit[$i][2] = exec ( "sed -n ''p /tmp/qos/flow-" . $i . "/tango.results'' | tail -1 | tr -s ' ' ' ' | cut -d  ' ' -f 13") ;
		$data_debit[$i][3] = $data_debit[$i][2]*0.001;
	}

$header = array('Flux', 'Debit (b/s)', 'Debit (kb/s)', 'Debit (Mb/s)' );

$pdf->FancyTable_debit($header,$data_debit);
$pdf->SetXY(10,175);
$pdf->TitreChapitre(3,"Image du debit");
$pdf->Image('/tmp/qos/debit.png',10,200,190, 50);




$pdf->AddPage();
$pdf->Ln();
$pdf->Ln();
$pdf->TitreChapitre(4,"Les resultats sur la latence");
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$nbflux = count(json_decode($json_tab));
$data_latence = array();
	for ($i=0; $i<$nbflux; $i++)
	{
		$data_latence[$i][0] = $i+1;
		$data_latence[$i][1] = exec ( "sed -n ''p /tmp/qos/flow-" . $i . "/fping.results'' | tail -1 | cut -d ' ' -f 8 | cut -d '/' -f 2")*100 ;
		$data_latence[$i][2] = exec ( "sed -n ''p /tmp/qos/flow-" . $i . "/fping.results'' | tail -1 | cut -d ' ' -f 8 | cut -d '/' -f 2") ;
		$data_latence[$i][3] = $data_latence[$i][1]*0.001;
	}

$header = array ('Flux', 'Latence (us)', 'Latence (ms)', 'Latence (s)' );
$pdf->FancyTable_debit($header,$data_latence);
$pdf->SetXY(10,175);
$pdf->TitreChapitre(5,"Graphique de la latence");
$pdf->Image('/tmp/qos/latence.png',10,200,190, 50);



$pdf->AddPage();
$pdf->Ln();
$pdf->Ln();
$pdf->TitreChapitre(6,"Les resultats des pertes de paquets");
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$nbflux = count(json_decode($json_tab));
$data_perte = array();
	for ($i=0; $i<$nbflux; $i++)
	{
		$data_perte[$i][0] = $i+1;
		$data_perte[$i][1] = exec ( "sed -n ''p /tmp/qos/flow-" . $i . "/fping.results'' | tail -1 | cut -d ' ' -f 5 | cut -d '/' -f 3 | cut -d '%' -f 1");
	}

$header = array('Flux', 'Perte (%)' );
$pdf->FancyTable_perte($header,$data_perte);
$pdf->SetXY(10,175);
$pdf->TitreChapitre(7,"graphique des pertes de paquets");
$pdf->Image('/tmp/qos/perte.png',10,200,190,50);



// $pdf->Output('/tmp/Exemplaire-client.pdf','F');

// POUR DEV : 
// $pdf->Output('/var/www/tango/results/' . $fichier_out, 'F');

// POUR PROD :
$pdf->Output('/var/www/results/' . $fichier_out, 'F');



//$cmd = "rm -f /tmp/tango.results && rm -f /tmp/fping.results";
//exec($cmd);
send_to_tangod("tango:savepdf");
echo "/results/" . $fichier_out ;

?>
