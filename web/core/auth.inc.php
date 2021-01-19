<?php


global $cfg;
global $link;
global $uid;
global $dict_bundle;

$link = mysql_connect($cfg['db_host'], $cfg['db_user'], $cfg['db_pass']);
if(!$link) { echo "Can't establish connection to MySQL database server."; exit; }

$dbsel = mysql_select_db($cfg['db_base'], $link);
if(!$dbsel) { echo "Can't switch working database."; exit; }

$res = mysql_query("SET NAMES 'utf8'");
if(!$res) { echo "Can't query on database."; exit; }

//include '/spell/src/common.php';
//$dict_bundle = new phpMorphy_FilesBundle('spell/dicts', 'rus');

// log in by cookie
if(isset($_COOKIE['login']) && isset($_COOKIE['passw'])) {
    $login = mysql_real_escape_string($_COOKIE['login']);
    $passw = mysql_real_escape_string($_COOKIE['passw']);
    $result = mysql_query("select * from `ithappens_auth` where `user_login` = '$login' and `user_passw` = '$passw' limit 1");
    if(mysql_num_rows($result) > 0) {
		$uid = mysql_fetch_assoc($result);

		$time_delta = time() - strtotime($uid['last_active']);
		if($time_delta < $cfg['user_online_time']) {
			$new_time = $uid['user_online'] + $time_delta;
			mysql_query("update `ithappens_auth` set `last_active` = now(), `user_online` = '${new_time}' where `user_id` = ${uid['user_id']}");
		} else {
			mysql_query("update `ithappens_auth` set `last_active` = now() where `user_id` = ${uid['user_id']}");
		}
		mysql_query("update `ithappens_auth` set `ip_last` = '${_SERVER['REMOTE_ADDR']}' where `user_id` = '${uid['user_id']}'");
		$uid['bookmarks'] = intval(mysql_result(mysql_query("SELECT count(*) FROM `ithappens_bookmark` WHERE `mark_user`='${uid['user_id']}'"), 0));
    }
}

function db_close() {
	global $link;
	mysql_close($link);
}

/* click */
mysql_query("update `it_visitors` set `clicks` = `clicks` + 1, `last` = now() where `date` = date(now()) limit 1");
if(!mysql_affected_rows()) {
	mysql_query("insert into `it_visitors` (`clicks`, `date`) values ('1', date(now()))");
}
$cfg['clicks'] = mysql_result(mysql_query("select `clicks` from `it_visitors` where `date` = date(now())"), 0, 0);
$cfg['clicks2'] = mysql_result(mysql_query("select sum(`clicks`) from `it_visitors` where 1"), 0, 0);

?>