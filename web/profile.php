<?php

require 'core/settings.inc.php';
require 'core/auth.inc.php';

db_close();

?>

<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
<TITLE>Профиль</TITLE>
<META NAME="AUTHOR" CONTENT="Roman Gemini">
<META NAME="CREATED" CONTENT="20110805;17490900">
<LINK REL=StyleSheet HREF="/ithappens.css" TYPE="text/css">
</HEAD>
<body>
<table width="100%" height="95%"><tr><td>
<div class="prof_head">Профиль пользователя:</div>
<table class="profile_table">
<tr><td>Логин:</td><td><b><?php echo $uid['user_login']; ?></b>, <a href="/logout/">Выйти</b></td></tr>
<tr><td>Зарегистрирован:</td><td><b><?php echo human_date($uid['user_regdate']);  ?></b></td></tr>
<tr><td>Полное имя:</td><td><b><?php echo $uid['user_fullname']; ?></b></td></tr>
<tr><td>Остановился на истории:</td><td><b><a href="/story/<?php echo $uid['st_current'].'">' . $uid['st_current']; ?></a></b></td></tr>
<tr><td>Всего прочитано:</td><td><b><a href="/story/<?php echo $uid['st_last'].'">'.$uid['st_last']; ?></a></b></td></tr>
<tr><td>Историй в закладках:</td><td><b><a href="/bookmarks/"><?php echo $uid['bookmarks']; ?></a></b></td></tr>
</table>
<br><div class="prof_head"><a href=".">Назад</a></div>
</td></tr></table>
</body>
</HTML>