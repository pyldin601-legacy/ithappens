<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');
$expire = time()+60*60*24*30;

  // check parameters for registration
if(isset($_POST['login']) && isset($_POST['passw'])) {
    $login = mysql_real_escape_string(stripslashes($_POST['login']));
    $passw = mysql_real_escape_string(stripslashes($_POST['passw']));
    $name  = mysql_real_escape_string(stripslashes($_POST['user']));
    // check parameters length
    if((strlen($login)<3) || (strlen($passw)<3))
    { $errmsg = 'Минимальная длина логина и пароля - 3 символа'; }
    else
    {
        // test user login existence
        $result = mysql_query("select `user_id` from `ithappens_auth` where `user_login`='$login'");
        if(mysql_num_rows($result))
        { $errmsg = 'Пользователь с таким логином уже зарегистрирован, введите другой логин!'; }
        else {
          // register user
          $md5pwd = md5($login.$passw);
          $result = mysql_query("insert into `ithappens_auth` (`user_login`, `user_passw`, `user_fullname`) values ('$login', '$md5pwd', '$name');");
			if(! $result) 
          { $errmsg = 'Невозможно создать учетную запись. Ошибка вставки в базу учетных данных.'; }
			else
          // user added sucessfully, login
          { 
            setcookie('login', $login, $expire, '/');
            setcookie('passw', $md5pwd, $expire, '/');
            header("Location: .");
            exit;
          }
        }
		db_close();
    }
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
<div class="prof_head">Регистрация пользователя</div><BR>
<form id="regform" action="register.php" method="post">
<table>
<tr id="reg">
<td width="60">Логин</td><td width=210><input id="text" name="login" size="30" maxlength="30" type="text" autofocus></td></tr>
<tr id="reg"><td>Пароль</td><td><input id="text" name="passw" size="30" maxlength="30" type="password"></td></tr>
<tr id="reg"><td>Имя</td><td><input id="text" name="user" size="30" maxlength="30" type="text"></td></tr>
<tr id="reg"><td colspan=2><center><input type="submit" value="Зарегистрировать"></center></td></tr>
<tr id="reg"><td colspan=2><center><a href="javascript:javascript:history.go(-1)">Назад</a> :: <a href="login.php">Авторизация</a></center></td></tr>
</table>
</form>
</td></tr></table>
</body>