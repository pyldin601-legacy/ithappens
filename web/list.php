<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');
require ('core/pages.php');
require ('core/header.inc.php');

// GET PAGE
if(isset($_GET['page']) and intval($_GET['page']))
	{ $page = $_GET['page']; }
else 
	{ $page = 1; }

if(isset($_GET['read']))
	{ $only_read = 1; $uri = '/readlist'; }
else 
	{ $only_read = 0; $uri = '/list'; }

if($only_read == 0)
	$qr = "select * from `ithappens` where 1 order by `story_id` desc";
else
	$qr = "select a.* from `ithappens` a, `ithappens_read` b where b.`user_id` = '${uid['user_id']}' and a.`story_id` = b.`story_id` order by b.`read_date` desc";

$result = mysql_query($qr);
$rows = mysql_num_rows($result);

$panel_contents = sh_topbar();
$pages = ceil($rows / ($cfg['titles_per_column'] * 2));

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
<TITLE>Содержание (страница <?php echo $page; ?>) - IT Happens</TITLE>
<link rel="icon" type="image/png" href="http://lolwut.ru/logo-ith.png">
<link rel="icon" type="image/gif" href="http://ithappens.ru/ith-icon.gif">
<link rel=StyleSheet href="/ithappens.css" type="text/css">
</HEAD>
<BODY>
<div class="topic">
<div class="panel"><?php echo $panel_contents; ?></div>
<?php search_frame(); ?>

<div class="navigation">
	<a href="javascript:javascript:history.go(-1);">Назад</a>
	<?php 
		if($uid['st_last']) echo " :: <a href='/story/" . $uid['st_last'] . "'>К последней прочитанной</a>"; 
	?>
</div>

<?php

echo '<div class="pages">' ; display_pages($uri, $pages, $page);  echo '</div>'; 

mysql_data_seek($result, ($page - 1) * ($cfg['titles_per_column'] * 2));
?>
<table class="list">
<tbody><tr>

<td>
<?php
$countr = 0;
while($countr < $cfg['titles_per_column'] * 2 and $row = mysql_fetch_assoc($result)) {
    $countr++;
    $s_id = $row['story_id'];
    $s_title = $row['story_title'];
	$s_views = intval($row['story_reads']);
	$s_read = intval(mysql_result(mysql_query("select count(*) from `ithappens_read` where `user_id` = '${uid['user_id']}' and `story_id` = '${s_id}' limit 1"), 0, 0));
?>
  <span class="<?php echo $s_read > 0 ? 'read' : 'unread'; ?>"><?php echo $s_id; ?>: <a href="/story/<?php echo $s_id; ?>"><?php echo $s_title; ?></a></span><span class="views"><?php echo ' (' . $s_views . ')'; ?></span><br>
<?php
//	if($countr == 50) echo '</td><td>';
}
?>

</td>
</tr></tbody>
</table>

<?php
echo '<div class="pages">' ; display_pages($uri, $pages, $page);  echo '</div>'; 
db_close();
?>

<div class="navigation"></div>
<div class="panel"><?php 	show_footer();  ?></div>
</div>
</BODY>
</HTML>