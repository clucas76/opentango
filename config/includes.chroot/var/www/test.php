<?php


$nbflux = 4;

$Debit_flux_1 = '1000';
$Latence_flux_1 = '10';
$Perte_flux_1 = '1';

$Debit_flux_2 = '2000';
$Latence_flux_2 = '20';
$Perte_flux_2 = '2';

$Debit_flux_3 = '3000';
$Latence_flux_3 = '30';
$Perte_flux_3 = '3';

$Debit_flux_4 = '4000';
$Latence_flux_4 = '40';
$Perte_flux_4 = '4';



//Préparation de la réponse
//on ouvre la réponse
$reponse = '{"' ;
for ($i=1; $i<=$nbflux; $i++){

// utilisation de variable dynamique
$Debit_flux_ = 'Debit_flux_'.$i;
$Latence_flux_ = 'Latence_flux_'.$i;
$Perte_flux_ = 'Perte_flux_'.$i;

$reponse = $reponse.'Debit_flux_'.$i.'":'.${$Debit_flux_}.', "Latence_flux_'.$i.'":'. $$Latence_flux_ .', "Perte_flux_'.$i.'":'.$$Perte_flux_.',';
}
//on supprime la derniére ,
$reponse = substr($reponse, 0, -1);
//on ferme la réponse
$reponse = $reponse.'}' ;

//on envoie la réponse
echo $reponse;

//echo '{"Debit_flux_X":'.$Debit_flux_X.', "Latence_flux_X":'. $Latence_flux_X.', "Perte_flux_":'.Perte_flux_.'}';



?>