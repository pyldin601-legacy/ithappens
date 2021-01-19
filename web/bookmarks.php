<?php

require 'core/settings.inc.php';
require 'core/auth.inc.php';
require 'core/pages.php';
require 'core/header.inc.php';

// GET PAGE
if(isset($_GET['page']) and intval($_GET['page']))
	$page = $_GET['page'];
else 
	$page = 1;

if(isset($_GET['user']) and intval($_GET['user']))
	$user = $_GET['user'];
elseif($uid) 
	$user = $uid['user_id'];
else
	$user = 0;

$bookmarks = mysql_result(mysql_query("SELECT count(*) FROM `ithappens_bookmark` WHERE `mark_user`='$user'"), 0, 0);
	
$panel_contents = sh_topbar();
$pages = ceil($bookmarks/$cfg['stories_per_page']);

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>";
echo "<HTML>";
echo "<HEAD>";
echo "<META HTTP-EQUIV='CONTENT-TYPE' CONTENT='text/html; charset=UTF-8'>";
echo "<TITLE>Избранное (страница $page) - IT Happens</TITLE>";
echo "<link rel='icon' type='image/png' href='http://lolwut.ru/logo-ith.png'>";
echo "<link rel='icon' type='image/gif' href='http://ithappens.ru/ith-icon.gif'>";
echo "<link rel='StyleSheet' href='/ithappens.css' type='text/css'>";
echo "</HEAD>";
echo "<BODY>";
echo "<div class='panel'>$panel_contents</div>";

search_frame(); 

echo "<div class='navigation'>";
echo "<a href='javascript:javascript:history.go(-1);'>Назад</a>";
if($uid['st_last']) echo " :: <a href='/bmarks.php'>Скачать закладки</a>"; 
echo "</div>";

echo '<div class="pages">' ; display_pages('/bookmarks', $pages, $page, ';' . $user) ; echo '</div>'; 

if($uid) {
	$result = mysql_query("SELECT `a`.* FROM `ithappens` a, `ithappens_bookmark` b WHERE `a`.`story_id`=`b`.`mark_story` and `b`.`mark_user`='$user' order by `a`.`story_id` desc");
    $rows = mysql_num_rows($result);
    if(!$rows) { 
		echo '<div class="frame">Нет историй в закладках.</div>'; goto bye; 
	} else {
		mysql_data_seek($result, ($page - 1) * $cfg['stories_per_page']);
		$countr = 0;
		while($row = mysql_fetch_assoc($result) and $countr < $cfg['stories_per_page']) {
			$countr++;
			$s_id = $row['story_id'];
			$s_title = $row['story_title'];
			$s_date = human_date($row['story_date']);
			$s_body = story_pg($row['story_body']);
			$s_tags = $row['story_tag'];
			$my_tags = sh_tags($s_tags);
			
			echo "<div class='title'><a href='/story/$s_id'>$s_id: $s_title</a></div>";
			echo "<div class='bar'>Опубликовано $s_date</div>";
			echo "<div class='sbody'><div style='padding:5px;'>$s_body</div></div>";
			echo "<div class='tags'>Теги: $my_tags</div>";
		}
    }
} else {
    echo "<BR>Никакие закладки у незарегистрированых пользователей! :-D<BR><BR>";
}

bye:
	echo "<div class='pages'>"; 
	display_pages('/bookmarks', $pages, $page, ';' . $user);
	echo '</div>'; 
	db_close();

	echo "<div class='navigation'>$navig_links</div>";

	echo "<div class='panel'>"; 
	show_footer(); 
	echo "</div>";

	echo "</BODY></HTML>";

?>