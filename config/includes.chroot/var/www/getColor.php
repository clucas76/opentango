<?php
function getColor($num) {
    $hash = '649B88FC5D5DBA9B61FF69B4FBF2B72F1B0C1FFED8606060A1068400FF00FF0921'; // liste de couleur
        $i= 6*$num;
	$r = substr($hash, -$i,6); 
$color = "#$r";
return $color;
}
?>

