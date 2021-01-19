<?php

#include './spell/src/common.php';


if(ob_start("gzencode")) {
	header('Content-encoding: gzip');
	header('Vary: accept-encoding');
} else {
	ob_start(); 
}

global $cfg;

$cfg['db_host'] = $_ENV['MYSQL_HOSTNAME'];
$cfg['db_user'] = $_ENV['MYSQL_USER'];
$cfg['db_pass'] = $_ENV['MYSQL_PASSWORD'];
$cfg['db_base'] = $_ENV['MYSQL_DATABASE'];

$cfg['stories_per_page'] = 5;
$cfg['titles_per_column'] = 25;
$cfg['user_online_time'] = 600;  /* Default: 10min = 600 */

date_default_timezone_set('Europe/Kiev');

function time_online($seconds) {

	$days = (int)($seconds / 86400) ? sprintf(" %d дн.", (int)($seconds / 86400)) : "";
	$hour = (int)($seconds / 3600) % 24 ? sprintf(" %d ч.", (int)($seconds / 3600) % 24) : "";
	$mins = (int)($seconds / 60) % 60 ? sprintf(" %d м.", (int)($seconds / 60) % 60) : "";
	$secs = sprintf(" %d с.", (int)$seconds % 60);
	return $days . $hour . $mins . $secs;
}

function human_date($indate) {

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

function story_pg($story) {
	$screening = false;
	$story = preg_replace('/(<br>)+$/', '', $story);
	$lines = explode("<br>", $story);
	foreach ($lines as &$value) {
		// crosslinking story
		$value = preg_replace('/(^|\s)#(\d+)/', "$1<a href=\".?story=$2\">#$2</a>", $value);
		$value = preg_replace('/http\:\/\/ithappens\.ru\/story\/(\d+)/', "/story/$1", $value);
		if (strlen($value)) {
			$value = "<p>$value</p>";
		} else {
			// empty line
			$value = "<br>";
		}
	}
	$result = implode("", $lines);
	//  $result = preg_replace('/(.*?)<br>/', "<p>$1</p", $result);
	return $result;
}

?>
