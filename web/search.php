<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');
require ('core/pages.php');
require ('core/header.inc.php');

// GET PAGE
if(isset($_GET['page'])) { 
	$page = (int) $_GET['page']; 
} else { 
	$page = 1; 
}
	
// GET MODE
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
} else { 
	$mode = 'title';
}

// GET QUERY
if(isset($_GET['query'])) {
	$query = mysql_real_escape_string($_GET['query']);
	$words = explode(' ', str_replace(array("'", '"', "<", ">", "\\", "/"), "", $query));
}
	
if($mode == 'tag') {
	$qr = "select * from `ithappens` where find_in_set('${query}', `story_tag`)";
} elseif($mode == 'title') {
	$qr = "select * from `ithappens` where ";
	foreach ($words as $key=>$word) {
		$qr .= "MATCH(story_title) AGAINST('${word}')";
		if($key + 1 < count($words)) $qr .= " and ";
	}
} elseif($mode == 'text') {
	$qr = "select * from `ithappens` where ";
	foreach ($words as $key=>$word) {
		$qr .= "MATCH(story_body) AGAINST('${word}')";
		if($key + 1 < count($words)) $qr .= " and ";
	}
} elseif($mode == 'all') {
	$qr = "select * from `ithappens` where ";
	if(preg_match('/^(\d+)$/', $query)) {
		$qr .= "(`story_id` = ${query}) or ";
	}
	foreach ($words as $key=>$word) {
		$qr .= "MATCH(story_title) AGAINST('${word}') or MATCH(story_body) AGAINST('${word}')";
		if($key + 1 < count($words)) $qr .= " and ";
	}
}

$result = mysql_query($qr);
$rows = mysql_num_rows($result);

$panel_contents = sh_topbar();
$pages = ceil($rows / $cfg['stories_per_page']);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
<TITLE>Страница <?php echo $page; ?> - IT Happens</TITLE>
<link rel="icon" type="image/png" href="http://lolwut.ru/logo-ith.png">
<link rel="icon" type="image/gif" href="http://ithappens.ru/ith-icon.gif">
<link rel=StyleSheet href="/ithappens.css" type="text/css">
</HEAD>
<BODY>
<div class="topic">
<div class="panel"><?php echo $panel_contents; ?></div>
<?php search_frame($query); ?>


<div class="navigation">
	<a href="javascript:javascript:history.go(-1);">Назад</a>
	<?php 
		if($uid['st_last']) echo " :: <a href='/story/" . $uid['st_current'] . "'>К последней прочитанной</a>"; 
	?>
</div>

<?php

echo '<div class="pages">' ; display_pages("/search/$mode/$query", $pages, $page);  echo '</div>'; 


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
?>
  <div class="title"><a href="/story/<?php echo $s_id; ?>"><?php echo $s_id; ?>: <?php echo $s_title; ?></a></div>
  <div class="bar">Опубликовано <?php echo $s_date; ?></div>
  <div class="sbody"><div style="padding:5px;"><?php echo $s_body; ?></div></div>
  <div class="tags">Теги: <?php echo $my_tags; ?></div>
<?php
}
if($countr == 0) echo '<div class="info">Ничего не найдено</div>';
echo '<div class="pages">' ; display_pages("/search/$mode/$query", $pages, $page);  echo '</div>'; 
db_close();
?>

<div class="navigation"><?php echo $navig_links; ?></div>
<div class="panel"><?php show_footer(); ?></div>
</div>
</BODY>
</HTML>