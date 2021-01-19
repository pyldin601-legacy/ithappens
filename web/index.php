<?php

require $_SERVER['DOCUMENT_ROOT'] . '/core/itmobile.php';
require $_SERVER['DOCUMENT_ROOT'] . '/core/core.php';


require 'core/settings.inc.php';
require 'core/auth.inc.php';
require 'core/header.inc.php';

require 'core/template.inc.php';


$itm = new itmobile();


$expire = time() + 60 * 60 * 24 * 30;
$s_time = date("Y-m-d G:i:s");

$default_story = firstValue(@$_GET['story'], @$uid['st_current'], @$_COOKIE['story'], 1);
	
if( $result = $itm->get_story($default_story) ) {
	$s_title = $result['story_title'];
	$s_date = human_date($result['story_date']);
	$s_body = story_pg($result['story_body']);
	$s_tags = $result['story_tag'];
	$s_reads = $result['story_reads'];
	$story_present = 1;
} else {
	echo "No story with is ID";
	exit;
}

setcookie('story', $default_story, $expire);

$my_tags = sh_tags($s_tags);

$names = "";
$pos = 0;

$books = mysql_query("SELECT b.* FROM `ithappens_bookmark` a, `ithappens_auth` b WHERE b.`user_id`=a.`mark_user` and `mark_story` = '${default_story}'");
while($row = mysql_fetch_assoc($books)) {
	$pos ++;
	$names .= '<a href="/profile.php?id=' . $row['user_id'] . '">' . $row['user_fullname'] . '</a>';
	if(mysql_num_rows($books) > $pos) $names .= ", ";
}

if($uid) {
	$ret = mysql_query("select * from `ithappens_read` where `user_id` = '${uid['user_id']}' and `story_id` = '${default_story}' limit 1");
	if($ret)
		$is_read = mysql_num_rows($ret);
	if(!$is_read) 
		mysql_query("insert into `ithappens_read` (`user_id`, `story_id`) values ('${uid['user_id']}', '${default_story}')");
}

$panel_contents = sh_topbar();
$navig_links = sh_navig();

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>";
echo "<HTML>";
echo "<HEAD>";
echo "<META HTTP-EQUIV='CONTENT-TYPE' CONTENT='text/html; charset=UTF-8'>";
echo "<meta content='initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes' name='viewport'>";
echo "<meta content='telephone=no' name='format-detection'>";
echo "<meta content='IE=edge' http-equiv='X-UA-Compatible'>";
echo "<meta content='176' name='MobileOptimized'>";
echo "<TITLE>$s_title - IT Happens</TITLE>";
echo "<link rel='icon' type='image/png' href='http://lolwut.ru/logo-ith.png'>";
echo "<link rel='icon' type='image/gif' href='http://ithappens.ru/ith-icon.gif'>";
echo "<link rel='StyleSheet' href='/ithappens.css' type='text/css'>";
echo "</HEAD>";
echo "<BODY>";
echo "<div class='panel'>$panel_contents</div>";
echo "<div class='title'><a href='/story/$default_story'>$default_story: $s_title</a></div>";
echo "<div class='bar'>От $s_date (просм.: $s_reads/$s_reads)</div>";
echo "<div class='sbody'><div style='padding:5px;'>$s_body</div></div>";
if($pos > 0) { echo "<div class='books'>Эта история по нраву: $names</div>"; } 
echo "<div class='tags'>Теги: $my_tags</div>";
echo "<div class='navigation'>$navig_links</div>";
echo "<div class='panel'>";
echo show_footer();
echo "</div>";
echo "</BODY></HTML>";

db_close();

?>
