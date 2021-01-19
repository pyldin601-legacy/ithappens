<?php

function firstValue() {
	foreach(func_get_args() as $arg)
		if(isset($arg))
			return $arg;
	
	return;
}

function rusDate($indate) {
	$dec2month = array(1 => "января", 2 => "февраля", 3 => "марта",     4 => "апреля",   5 => "мая",     6 => "июня", 
                 	   7 => "июля",   8 => "августа", 9 => "сентября", 10 => "октября", 11 => "ноября", 12 => "декабря");

        $date = getdate(strtotime($indate));
        $year = $date['year'];
        $month = $dec2month[$date['mon']];
        $day = $date['mday'];
        $hour = $date['hours'];
        $min = $date['minutes'];
        $result = sprintf("%1d %s %4d, %1d:%02d", $day, $month, $year, $hour, $min);
        return $result;
}


?>
