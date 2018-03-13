<?php

require('fpdf.php');

class PDF extends FPDF
{
function Header()
{
    global $titre;

    $this->Image('logo.png',10,6,30);
    // Arial gras 15
    $this->SetFont('Arial','B',15);
    // Calcul de la largeur du titre et positionnement
    $w = $this->GetStringWidth($titre)+6;
    $this->SetX((210-$w)/2);
    // Couleurs du cadre, du fond et du texte
    $this->SetDrawColor(0,0,0);  // bleu (0,80,180)
    $this->SetFillColor(220,50,50); // jaune (230,230,0)
    $this->SetTextColor(255,255,255); // rouge (220,50,50)
    // Epaisseur du cadre (1 mm)
    $this->SetLineWidth(1);
    // Titre
    $this->Cell($w,9,$titre,1,1,'C',true);
    // Saut de ligne
    $this->Ln(10);
}

function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Arial italique 8
    $this->SetFont('Arial','I',8);
    // Couleur du texte en gris
    $this->SetTextColor(128);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}


function TitreChapitre($num, $libelle)
{
    // Arial 12
    $this->SetFont('Arial','',12);
    // Texte
    $this->SetTextColor(255,255,255); 
    // Couleur de fond
    $this->SetFillColor(220,50,50); //gris
    // Titre
    $this->Cell(0,6,"$num. $libelle",0,1,'L',true);
    // Saut de ligne
    $this->Ln(4);
    // Reset pour ecrire en noir
    $this->SetTextColor(0,0,0); 
}
function Recap($fichier,$NomClient,$Lieu,$NomTest,$NomTech)
{
    // Lecture du fichier texte
$txt = file_get_contents($fichier);
$file = file($fichier);
$decal = "40";
$NbLigne = count($file);

    $HdebutDate = exec ( "sed -n 1p $fichier | tr -s ' ' ' ' | cut -d  ' ' -f 2") ;
    $HdebutHeure = exec ( "sed -n 1p $fichier | tr -s ' ' ' ' | cut -d  ' ' -f 3") ;
    $Hdebut = "$HdebutDate - $HdebutHeure";
    $HfinDate = exec ( "sed -n $NbLigne''p $fichier | tr -s ' ' ' ' | cut -d  ' ' -f 2") ;
    $HfinHeure = exec ( "sed -n $NbLigne''p $fichier | tr -s ' ' ' ' | cut -d  ' ' -f 3") ;
    $Hfin = "$HfinDate - $HfinHeure";
    $DureeDuTest = gmdate('H:i:s',(strtotime("$HfinHeure"))-(strtotime("$HdebutHeure")));

    
    // Times 12
    $this->SetFont('Arial','',12);
    $this->Cell(10,6,"Test: ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$NomTest,0,1,'L');

    $this->Cell(10,6,"Heure de debut : ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$Hdebut,0,1,'L');

    $this->Cell(10,6,"Heure de fin : ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$Hfin,0,1,'L');

    $this->Cell(10,6,"Duree du test : ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$DureeDuTest,0,1,'L');

    $this->Cell(10,6,"Client : ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$NomClient,0,1,'L');

    $this->Cell(10,6,"Lieu : ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$Lieu,0,1,'L');

    $this->Cell(10,6,"Technicien : ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,$NomTech,0,1,'L');
}

function LineGraph($w, $h, $data, $options='', $colors=null, $maxVal=0, $nbDiv=4){
        /*******************************************
        Explain the variables:
        $w = the width of the diagram
        $h = the height of the diagram
        $data = the data for the diagram in the form of a multidimensional array
        $options = the possible formatting options which include:
            'V' = Print Vertical Divider lines
            'H' = Print Horizontal Divider Lines
            'kB' = Print bounding box around the Key (legend)
            'vB' = Print bounding box around the values under the graph
            'gB' = Print bounding box around the graph
            'dB' = Print bounding box around the entire diagram
        $colors = A multidimensional array containing RGB values
        $maxVal = The Maximum Value for the graph vertically
        $nbDiv = The number of vertical Divisions
        *******************************************/
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.2);
        $keys = array_keys($data);
        $ordinateWidth = 10;
        $w -= $ordinateWidth;
        $valX = $this->getX()+$ordinateWidth;
        $valY = $this->getY();
        $margin = 1;
        $titleH = 8;
        $titleW = $w;
        $lineh = 5;
        $keyH = count($data)*$lineh;
        $keyW = $w/5;
        $graphValH = 5;
        $graphValW = $w-$keyW-3*$margin;
        $graphH = $h-(3*$margin)-$graphValH;
        $graphW = $w-(2*$margin)-($keyW+$margin);
        $graphX = $valX+$margin;
        $graphY = $valY+$margin;
        $graphValX = $valX+$margin;
        $graphValY = $valY+2*$margin+$graphH;
        $keyX = $valX+(2*$margin)+$graphW;
        $keyY = $valY+$margin+.5*($h-(2*$margin))-.5*($keyH);
        //draw graph frame border
        if(strstr($options,'gB')){
            $this->Rect($valX,$valY,$w,$h);
        }
        //draw graph diagram border
        if(strstr($options,'dB')){
            $this->Rect($valX+$margin,$valY+$margin,$graphW,$graphH);
        }
        //draw key legend border
        if(strstr($options,'kB')){
            $this->Rect($keyX,$keyY,$keyW,$keyH);
        }
        //draw graph value box
        if(strstr($options,'vB')){
            $this->Rect($graphValX,$graphValY,$graphValW,$graphValH);
        }
        //define colors
        if($colors===null){
            $safeColors = array(0,51,102,153,204,225);
            for($i=0;$i<count($data);$i++){
                $colors[$keys[$i]] = array($safeColors[array_rand($safeColors)],$safeColors[array_rand($safeColors)],$safeColors[array_rand($safeColors)]);
            }
        }
        //form an array with all data values from the multi-demensional $data array
        $ValArray = array();
        foreach($data as $key => $value){
            foreach($data[$key] as $val){
                $ValArray[]=$val;                    
            }
        }
        //define max value
        if($maxVal<ceil(max($ValArray))){
            $maxVal = ceil(max($ValArray));
	$maxVal = ($maxVal + (50*$maxVal/100));
        }
        //draw horizontal lines
        $vertDivH = $graphH/$nbDiv;
        if(strstr($options,'H')){
            for($i=0;$i<=$nbDiv;$i++){
                if($i<$nbDiv){
                    $this->Line($graphX,$graphY+$i*$vertDivH,$graphX+$graphW,$graphY+$i*$vertDivH);
                } else{
                    $this->Line($graphX,$graphY+$graphH,$graphX+$graphW,$graphY+$graphH);
                }
            }
        }
        //draw vertical lines
        $horiDivW = floor($graphW/(count($data[$keys[0]])-1));
        if(strstr($options,'V')){
            for($i=0;$i<=(count($data[$keys[0]])-1);$i++){
                if($i<(count($data[$keys[0]])-1)){
                    $this->Line($graphX+$i*$horiDivW,$graphY,$graphX+$i*$horiDivW,$graphY+$graphH);
                } else {
                    $this->Line($graphX+$graphW,$graphY,$graphX+$graphW,$graphY+$graphH);
                }
            }
        }
        //draw graph lines
        foreach($data as $key => $value){
            $this->setDrawColor($colors[$key][0],$colors[$key][1],$colors[$key][2]);
            $this->SetLineWidth(0.8);
            $valueKeys = array_keys($value);
            for($i=0;$i<count($value);$i++){
                if($i==count($value)-2){
                    $this->Line(
                        $graphX+($i*$horiDivW),
                        $graphY+$graphH-($value[$valueKeys[$i]]/$maxVal*$graphH),
                        $graphX+$graphW,
                        $graphY+$graphH-($value[$valueKeys[$i+1]]/$maxVal*$graphH)
                    );
                } else if($i<(count($value)-1)) {
                    $this->Line(
                        $graphX+($i*$horiDivW),
                        $graphY+$graphH-($value[$valueKeys[$i]]/$maxVal*$graphH),
                        $graphX+($i+1)*$horiDivW,
                        $graphY+$graphH-($value[$valueKeys[$i+1]]/$maxVal*$graphH)
                    );
                }
            }
            //Set the Key (legend)
            $this->SetFont('Courier','',10);
            if(!isset($n))$n=0;
            $this->Line($keyX+1,$keyY+$lineh/2+$n*$lineh,$keyX+8,$keyY+$lineh/2+$n*$lineh);
            $this->SetXY($keyX+8,$keyY+$n*$lineh);
            $this->Cell($keyW,$lineh,$key,0,1,'L');
            $n++;
        }
        //print the abscissa values
        foreach($valueKeys as $key => $value){
            if($key==0){
                $this->SetXY($graphValX,$graphValY);
                $this->Cell(30,$lineh,$value,0,0,'L');
            } else if($key==count($valueKeys)-1){
                $this->SetXY($graphValX+$graphValW-30,$graphValY);
                $this->Cell(30,$lineh,$value,0,0,'R');
            } else {
                $this->SetXY($graphValX+$key*$horiDivW-15,$graphValY);
                $this->Cell(30,$lineh,$value,0,0,'C');
            }
        }
        //print the ordinate values
        for($i=0;$i<=$nbDiv;$i++){
            $this->SetXY($graphValX-10,$graphY+($nbDiv-$i)*$vertDivH-3);
            $this->Cell(8,6,sprintf('%.1f',$maxVal/$nbDiv*$i),0,0,'R');
        }
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.2);
    }

function GraphDatarateRx ($fichier,$fichierBw){

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;

  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			global $datarate;
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
   			$bpsrx1 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 1") ;
			$bpsrx2 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 3") ;
   			$Time = exec ( "sed -n $i''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$bps = round ( ((($bpsrx2-$bpsrx1)*8)/$Time),0);
			$bps = $bps * 0.001;
			$u_fping = $u_fping+1;
			$flag = 0;
			global $datarate;
			$datarate["$LongueurDeTrame"] = $bps;
			}

	}
global $datarate;
$datarate["0"] = 0;

$this->SetFont('Arial','',10);

$data = array(

'Datarate' => $datarate
);
$colors = array(
    'Datarate' => array(114,171,237)
);

// Options d'affichage : toutes (lignes horizontales et verticales, 4 bordures)
// Couleurs : fixes
// Ordonnée maximale : 6
// Nombre de divisions : 3
$this->LineGraph(190,100,$data,'VHkBvBgBdB',$colors,20,20);


} 
function TableauDatarateRx($fichier,$fichierBw){
$decal = "70";


    // Times 12
    $this->SetFont('Arial','',12);
    $this->Cell(10,6,"Datarate (bits/s) ",0,1,'L');
    $this->Cell(10,6,"_________________________________________________________ ",0,1,'L');

    $this->Cell(10,6,"\n ",0,1,'L');
    $this->Cell(10,6,"Longueur de trame (octets) ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,"Datarate en TX  (bits/s)         (kbits/s)         (Mbits/s)",0,1,'L');

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;

  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$bpsrx1 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 1") ;
			$bpsrx2 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 3") ;
   			$Time = exec ( "sed -n $i''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$Datarate = round ( (($bpsrx2-$bpsrx1)*8/$Time),0);
			$Datarate_kb = $Datarate*0.001;
			$Datarate_mb = $Datarate_kb*0.0001;
			$u_fping = $u_fping+1;
   			$flag = 0;

			$this->Cell(10,6,$LongueurDeTrame,0,0,'L');
    			$this->Cell($decal);
   			$this->Cell(10,6,"                           $Datarate       $Datarate_kb      $Datarate_mb",0,1,'L');
			}

	}


}


function GraphDatarateTx ($fichier,$fichierBw){

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;
global $datarate;
			$datarate["0"] = 0;
  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			global $datarate;
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
   			$bpstx1 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 2") ;
			$bpstx2 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 4") ;
   			$Time = exec ( "sed -n $i''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$bps = round ( ((($bpstx2-$bpstx1)*8)/$Time),0);
			$bps = $bps * 0.001;
			$u_fping = $u_fping+1;
			$flag = 0;
			$datarate["$LongueurDeTrame"] = $bps;
			}

	}
global $datarate;
$datarate["0"] = 0;
$this->SetFont('Arial','',10);

$data = array(

'Datarate' => $datarate
);
$colors = array(
    'Datarate' => array(114,171,237)
);

// Options d'affichage : toutes (lignes horizontales et verticales, 4 bordures)
// Couleurs : fixes
// Ordonnée maximale : 6
// Nombre de divisions : 3
$this->LineGraph(190,100,$data,'VHkBvBgBdB',$colors,20,20);


} 
function TableauDatarateTx($fichier,$fichierBw){
$decal = "70";


    // Times 12
    $this->SetFont('Arial','',12);
    $this->Cell(10,6,"Datarate en Tx (bits/s) ",0,1,'L');
    $this->Cell(10,6,"_________________________________________________________ ",0,1,'L');

    $this->Cell(10,6,"\n ",0,1,'L');
    $this->Cell(10,6,"Longueur de trame (octets) ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,"Datarate en TX  (bits/s)         (kbits/s)         (Mbits/s)",0,1,'L');

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;

  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$bpstx1 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 2") ;
			$bpstx2 = exec ( "sed -n $u_fping''p $fichierBw'' | tr -s ' ' ' ' | cut -d  ' ' -f 4") ;
   			$Time = exec ( "sed -n $i''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$Datarate = round ( (($bpstx2-$bpstx1)*8/$Time),0);
			$Datarate_kb = $Datarate*0.001;
			$Datarate_mb = $Datarate_kb*0.0001;
			$u_fping = $u_fping+1;
   			$flag = 0;

			$this->Cell(10,6,$LongueurDeTrame,0,0,'L');
    			$this->Cell($decal);
   			$this->Cell(10,6,"                           $Datarate       $Datarate_kb      $Datarate_mb",0,1,'L');
			}

	}


}




function TableauTauxDeTrame($fichier){
$decal = "70";
    // Times 12
    $this->SetFont('Arial','',12);
    $this->Cell(10,6,"Taux de trame (trame/s) ",0,1,'L');
    $this->Cell(10,6,"_________________________________________________________ ",0,1,'L');

    $this->Cell(10,6,"\n ",0,1,'L');
    $this->Cell(10,6,"Longueur de trame (octets) ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,"Taux de trame (trame/s)",0,1,'L');

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
 
  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
   			$rcvd = exec ( "sed -n $i''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 4 | cut -d  '/' -f 2 ") ;
                        $Time = exec ( "sed -n $i''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$TauxDeTrame = round ( ($rcvd/$Time),0);
   			$flag = 0;

			$this->Cell(10,6,$LongueurDeTrame,0,0,'L');
    			$this->Cell($decal);
   			$this->Cell(10,6,$TauxDeTrame,0,1,'L');
			}

	}

}


function GraphLatence ($fichier,$fichier_fping){

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;
  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			global $datarate;
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
   			$LatenceM = exec ( "sed -n $u_fping''p $fichier_fping'' | tr -s ' ' ' ' | cut -d  ' ' -f 8 | cut -d  '/' -f 2") ;
			$LatenceM = $LatenceM * 1000;
   			$flag = 0;
			$u_fping = $u_fping+1;
			global $Latence;
			$Latence["$LongueurDeTrame"] = $LatenceM;
			}

	}

$this->SetFont('Arial','',10);

$data = array(

'Latence' => $Latence
);
$colors = array(
    'Latence' => array(114,171,237)
);


// Options d'affichage : toutes (lignes horizontales et verticales, 4 bordures)
// Couleurs : fixes
// Ordonnée maximale : 6
// Nombre de divisions : 3
$this->LineGraph(190,100,$data,'VHkBvBgBdB',$colors,20,20);


} 


function TableauLatence($fichier,$fichier_fping){
$decal = "70";

    // Times 12
    $this->SetFont('Arial','',12);
    $this->Cell(10,6,"Latence (micro seconde) ",0,1,'L');
    $this->Cell(10,6,"_________________________________________________________ ",0,1,'L');

    $this->Cell(10,6,"\n ",0,1,'L');
    $this->Cell(10,6,"Longueur de trame (octets) ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,"Latence (micro seconde)",0,1,'L');

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;

  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
   			$LatenceM = exec ( "sed -n $u_fping''p $fichier_fping'' | tr -s ' ' ' ' | cut -d  ' ' -f 8 | cut -d  '/' -f 2") ;
			$LatenceM = $LatenceM * 1000;  //conversion micro seconde
   			$flag = 0;
			$u_fping = $u_fping+1; //Flag pour le fichier_fping

			$this->Cell(10,6,$LongueurDeTrame,0,0,'L');
    			$this->Cell($decal);
   			$this->Cell(10,6,$LatenceM,0,1,'L');
			}

	}


}



function TableauTauxDeTramePerdu($fichier,$fichier_fping){
$decal = "70";
    // Times 12
    $this->SetFont('Arial','',12);
    $this->Cell(10,6,"Taux de trame Perdu (%)",0,1,'L');
    $this->Cell(10,6,"_________________________________________________________ ",0,1,'L');

    $this->Cell(10,6,"\n ",0,1,'L');
    $this->Cell(10,6,"Longueur de trame (octets) ",0,0,'L');
    $this->Cell($decal);
    $this->Cell(10,6,"Taux de trame Perdu (%)",0,1,'L');

$fichierModifie = 'resultat-modifie.txt';
$file = file($fichier); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
$nbligne = count($file); // compter nb lignes
$i = ($nbligne-1);

unset($file[0]); // supprime la première ligne
unset($file[$i]); // supprime la derniere ligne 

file_put_contents($fichierModifie, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.
$fileModifie = file($fichierModifie);
$nbligneModifie = count($fileModifie);
$flag=0;
$u_fping=1;
 
  for($i=1; $i< ($nbligneModifie+1); $i++)
	{	
		if ($flag == 0){
   				$flag = 1;
				}
		else{
			
			
			$u=$i-1;
			$LongueurDeTrame = exec ( "sed -n $u''p $fichierModifie'' | tr -s ' ' ' ' | cut -d  ' ' -f 10") ;
			$TauxDeTramePerdu = exec ( "sed -n $u_fping''p $fichier_fping'' | tr -s ' ' ' ' | cut -d  ' ' -f 5 | cut -d  '/' -f 3  | cut -d ',' -f 1 | cut -d '%' -f 1") ;
   			$flag = 0;
			$u_fping = $u_fping+1;

			$this->Cell(10,6,$LongueurDeTrame,0,0,'L');
    			$this->Cell($decal);
   			$this->Cell(10,6,$TauxDeTramePerdu,0,1,'L');
			}

	}

}

//fin class PDF
}
?>

