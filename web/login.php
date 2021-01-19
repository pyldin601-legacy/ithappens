<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');

$expire = time()+60*60*24*30;

  // check parameters for registration
if(isset($_POST['login']) && isset($_POST['passw'])) {
    $login = mysql_real_escape_string($_POST['login']);
    $passw = md5($login.$_POST['passw']);
} elseif (isset($_COOKIE['login']) && isset($_COOKIE['passw'])) {
    $login = mysql_real_escape_string($_COOKIE['login']);
    $passw = mysql_real_escape_string($_COOKIE['passw']);
}

if(isset($_GET['error'])) {
    switch($_GET['error']) {
      case 1: {
        $errmsg = 'Для того чтобы добавить историю в закладки необходимо авторизоваться'; break; }
    }
}

$result = mysql_query("select * from `ithappens_auth` where `user_login`='$login' and `user_passw`='$passw'");
if(! mysql_num_rows($result) ) { 
	$errmsg = 'Неверный логин или пароль!'; 
	db_close();
} else {
	setcookie('login', $login, $expire, '/');
	setcookie('passw', $passw, $expire, '/');
	header("Location: /");
	db_close();
	exit;
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
<TITLE>IT Happens (local)</TITLE>
<META NAME="AUTHOR" CONTENT="Roman Gemini">
<META NAME="CREATED" CONTENT="20110805;17490900">
<LINK REL=StyleSheet HREF="ithappens.css" TYPE="text/css">
</HEAD>

<body>
<table width=100% height=90%><tr id="window"><td>
<div id="error"><?php echo $errmsg; ?></div>
<div class="prof_head">Авторизация</div><BR>
<form id="regform" action="login.php" method="post">
<table>
<tr id="reg">
<td width="60">Логин</td><td width=210><input id="text" name="login" size="30" maxlength="30" type="text" autofocus></td></tr>
<tr id="reg"><td>Пароль</td><td><input id="text" name="passw" size="30" maxlength="30" type="password"></td></tr>
<tr id="reg"><td colspan=2><center><input type="submit" value="Войти"></center></td></tr>
<tr id="reg"><td colspan=2><center><a href="javascript:javascript:history.go(-1)"><BR>Назад</a> :: <a href="register.php">Регистрация</a></center></td></tr>
</table>
</form>
</td></tr></table>
</body>