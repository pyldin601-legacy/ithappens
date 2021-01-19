<?php

function sh_topbar() {
	global $uid;
	global $cfg;
	global $link;
	global $default_story;
	$out = '';

	// display auth block
	$online = (int)mysql_result(mysql_query("select count(*) from `ithappens_auth` where timediff(now(), `last_active`) <= ${cfg['user_online_time']}"), 0, 0);

	if($uid) {
		$auth_user = strlen($uid['user_fullname']) ? $uid['user_fullname'] : $uid['user_login'];
		$read_by = (int)mysql_result(mysql_query("select count(*) from `ithappens_read` where `user_id` = '${uid['user_id']}'"), 0, 0);
		$lb = "<td><a href='/web/profile.php'>Мой<br>профиль</a></td>";
	} else {
		$read_by = 0;
		$lb = '<td><a href="/login.php?st=' . $default_story . '">Войти</a></td>';
	}

	//$out = '<DIV ID="header">';
	$cfg['stories_total'] = mysql_result(mysql_query("select count(*) from `ithappens`"), 0, 0);
	$out .= '<table class="newpanel"><tr>';


	$out .= '<td><a href="/list/">Ист.<br><span class="sub">' . $cfg['stories_total'] . '</span></a></td>';
	$out .= '<td><a href="/bookmarks/">Закл.<br><span class="sub">' . (int)$uid['bookmarks'] . '</span></a></td>';
	$out .= '<td><a href="/readlist/">Проч.<br><span class="sub">' . $read_by . '</span></a></td>';
	$out .= '<td><a href="/users.php">Польз.<br><span class="sub">' . $online . '</span></a></td>';
	$out .= $lb;
	$out .= '</tr></table>';
	$out .= '<script src="/js/query.js"></script>';

	return $out;
}

function sh_navig() {
	global $default_story;
	global $uid;
	global $cfg;
	global $link;
	$output = "";
	// DOES STORY BOOKMARKED
	if($uid) {
		$result = mysql_query("SELECT * FROM `ithappens_bookmark` WHERE `mark_story` = '${default_story}' and `mark_user` = '${uid['user_id']}' limit 1");
		$bookmarked = mysql_num_rows($result);
	} else {
		$bookmarked = 0;
	}
	$result = mysql_query("select `story_id` from `ithappens` where `story_id`<'${default_story}' order by `story_id` desc limit 1");
	if (mysql_num_rows($result)) { $s_prev = mysql_result($result, 0, 0); }
	$result = mysql_query("select `story_id` from `ithappens` where `story_id`>'${default_story}' order by `story_id` limit 1");
	if (mysql_num_rows($result)) { $s_next = mysql_result($result, 0, 0); }


	
	if(isset($s_prev)) { $output .= "<a href='/story/$s_prev'>Назад</a> ::"; }
	if(!$bookmarked) {
		if($uid) 
			$output .= " <a href='/bookmark/${default_story}'>В закладки</a> ";
		else
			$output .= " X ";
	} else 
		$output .= " <a href='/bookmark/${default_story}' style='color:green;'>В закладках</a> ";

	if(isset($s_next)) { $output .= ":: <a href='/story/$s_next'>Вперед</a> "; }
	return $output;
}

function sh_tags($tags) {
	$output = "";
	$tarr = explode(",", $tags);
	foreach($tarr as $key=>$titm) {
		$output .= '<nobr><a href="/search/tag/' . urlencode($titm) . '/1">' . $titm . '</a></nobr>';
		if($key < count($tarr) - 1) $output .= ', ';
	}
	return $output;
}

function search_frame($query = '') {

	echo '<div class="search">';
	echo 'Поиск: ';
	echo '<input style="width:150px;" type="text" id="query" value="'.$query.'" onkeyup="findEnter(event);" autofocus>';
	echo '<input style="width:70px;" type="button" value="Найти" onclick="findText();">';
	echo '</div>';
	return;

}

function trim_sumbols($str_input = '') {
	return $str_input;
}

function result_hl ($intext, $query) {
  if(!count($query)) return $intext;
  foreach($query as $que) {
	if($que) {
		$fixthis = '<span class="hl"></span>';
		if(!strpos($fixthis, strtolower($que))) {
			$quote = preg_quote($que);
			$intext = preg_replace("/($quote)/i", '<span class="hl">$1</span>', $intext);
		}
	}
  }
  return $intext;
}

function show_footer() {
	global $cfg;
	echo sprintf('© 2008—%4d, lolwut. Powered by finely trained slowpokes! (<a href="http://ithappens.ru">ithappens.ru</a>) [%d:%d]', date("Y"), $cfg['clicks'], $cfg['clicks2']);
}


?>
