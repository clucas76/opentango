<?php

function random($car) 
{
        $string = "";
        $chaine = "0123456789abcdef";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
                $string .= $chaine[rand()%strlen($chaine)];
		if ($i == 1) 
			$string .= ":";
		if ($i == 3) 
			$string .= ":";

		if ($i == 5) 
			$string .= ":";

		if ($i == 7) 
			$string .= ":";

		if ($i == 9) 
			$string .= ":";

        }
        return $string;
}  

$str = random(12);
echo $str . "\n";

?>
