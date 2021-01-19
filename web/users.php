<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');
require ('core/header.inc.php');

$panel_contents = sh_topbar();

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
<TITLE>Пользователи в сети - IT Happens</TITLE>
<link rel="icon" type="image/png" href="http://lolwut.ru/logo-ith.png">
<link rel="icon" type="image/gif" href="http://ithappens.ru/ith-icon.gif">
<link rel="StyleSheet" href="/ithappens.css" type="text/css">
</HEAD>
<BODY>
<div class="topic">
<div class="panel"><?php echo $panel_contents; ?></div>

<div class="prof_head">Пользователи</div><BR>
<table class="userlist" cellspacing="0" cellpadding="0">
<tr><th class="al">Пользователь</th><th class="ar">Позиция</th><th class="ar">Провел на сайте</th></tr>
<?php
	$res = mysql_query("select * from `ithappens_auth` where 1 order by `last_active` desc");
	if($res) {
		while($row = mysql_fetch_assoc($res)) {
			echo '<tr>';
			if(time() - strtotime($row['last_active']) < $cfg['user_online_time']) {
				echo '<td class="al"><img src="images/on.png">' . $row['user_fullname'] . '</td>';
			} else {
				echo '<td class="al"><img src="images/off.png">' . $row['user_fullname'] . '</td>';
			}
			echo '<td class="ar">' . $row['st_current'] . '</td>';
			echo '<td class="ar">' . time_online($row['user_online']) . '</td>';
			echo '</tr>' . "\n";
		}
	}
?>
</table>
<div class="panel">
<?php 
	show_footer();
	db_close();
?>
</div>
</div>
</BODY>
