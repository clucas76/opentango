<?php

require('fonction.php');
include_once('../comm.inc.php');



$pdf = new PDF();
$titre = 'Resultat du Test';
$fichier = '/tmp/tango.results';
$fichier_fping = '/tmp/fping.results';
$fichier_bwdebit = '/tmp/bwdebit.out';
$NomTest = "Test debit";
$NomClient = "Completel";
$Lieu = "Isneauville";
$NomTech = "n/a";

$NomTest	= $_GET['nom_test'];
$NomTest	= "Test OpenTango Niveau 3";
$NomClient 	= $_GET['nom_client'] ;
$Lieu		= $_GET['lieu'] ;
$ip_src		= $_GET['ip_src'];
$ip_dst		= $_GET['ip_dst'];
$today 		= date("d-m-Y-H-i-s");
$NomTech	= $_GET['nom_tech'] ;
$Consigne	= $_GET['debit'];
$paquet		= $_GET['paquet'];
$tpc		= $_GET['tpc'];
$tos		= $_GET['tos'];
$nego		= $_GET['nego'];

$fichier_out = "Resultats-" . $NomClient . "-" . $Lieu . "-" . $today . ".pdf";


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
$pdf->TitreChapitre(1,"Recapitulatif du test");
$pdf->Recap($fichier,$NomClient,$Lieu,$NomTest,$NomTech, $Consigne, $paquet, $tpc, $tos, $ip_src, $ip_dst, $nego);
$pdf->AddPage();
$pdf->TitreChapitre(2,"Debit en Rx = f(lg de trame)");
$pdf->GraphDatarateRx($fichier,$fichier_bwdebit);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->TableauDatarateRx($fichier,$fichier_bwdebit);
$pdf->AddPage();
$pdf->TitreChapitre(3,"Debit en Tx = f(lg de trame)");
$pdf->GraphDatarateTx($fichier,$fichier_bwdebit);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->TableauDatarateTx($fichier,$fichier_bwdebit);
$pdf->AddPage();
$pdf->TitreChapitre(4,"Taux de trame");
$pdf->TableauTauxDeTrame($fichier);
$pdf->TableauTauxDeTramePerdu($fichier,$fichier_fping);
$pdf->Ln();
$pdf->Ln();
$pdf->AddPage();
$pdf->TitreChapitre(5,"Latence = f(lg de trame)");
$pdf->GraphLatence($fichier,$fichier_fping);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->TableauLatence($fichier,$fichier_fping);
// $pdf->Output('/tmp/Exemplaire-client.pdf','F');

// POUR DEV : 
// $pdf->Output('/var/www/tango/results/' . $fichier_out, 'F');

// POUR PROD :
$pdf->Output('/var/www/results/' . $fichier_out, 'F');


$cmd = "rm -f /tmp/tango.results && rm -f /tmp/fping.results";
exec($cmd);
send_to_tangod("tango:savepdf");
echo "/results/" . $fichier_out ;

?>
