<?php
//
// Tangod -- Copyright 2014-2015 : 
// Christophe Lucas <christophe@clucas.fr>
// Ludovic Leroy <>
// 
// Lanceur de programme / configure la machine pour faire les tests tango, ...
//

function bgexec($str, $redir)
{
        $command = 'taskset 0x1 nohup '.$str.' > ' . $redir . ' 2>&1 & echo $!';
	logging_debug("BGEXEC>> " . $command);
        exec($command ,$op);
        $pid = (int)$op[0];

	return $pid;
}


function bgexec_append($str, $redir)
{
        $command = 'taskset 0x1 nohup '.$str.' >> ' . $redir . ' 2>&1 & echo $!';
	logging_debug("BGEXEC>> " . $command);
        exec($command ,$op);
        $pid = (int)$op[0];

	return $pid;
}

?>
