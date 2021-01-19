<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">

<?php

  // variables
  require ('core/database.inc.php');

  $story = mysql_real_escape_string(stripslashes($_POST['story']));
  $comment = mysql_real_escape_string(($_POST['comment']));

  if(!isset($story, $comment)) {
    exit;
  }

  // INIT DATABASE //
  $link = mysql_connect($db_host, $db_user, $db_pass);
  $dbsel = mysql_select_db($db_base, $link);
  mysql_query("SET NAMES 'utf8'");

  // DOES THIS STORY PRESENT
  $result = mysql_query("select * from `ithappens` where `id_story`=\"$story\"");
  $rows = mysql_affected_rows();

  if(!$rows) {
    echo "Истории с таким номером нет в базе данных!";
    exit;
  }

  // GET LOGIN ID
  if(isset($_COOKIE['login']) && isset($_COOKIE['passw'])) {
    $login = mysql_real_escape_string(stripslashes($_COOKIE['login']));
    $passw = mysql_real_escape_string(stripslashes($_COOKIE['passw']));
    $result = mysql_query("select * from `ithappens_auth` where `user_login`=\"$login\" and `user_passw`=\"$passw\" limit 1");
    if(mysql_num_rows($result)) {
      $row = mysql_fetch_assoc($result);
      $userid = $row['user_id'];
    } else {
      echo 'Вы не авторизованы!';
      exit;
    }
  }

  // POST COMMENT
  $result = mysql_query("insert into `ithappens_comments` (`comment_user`, `comment_story`, `comment_body`) values (\"$userid\", \"$story\", \"$comment\");");
  if(!$result) {
    echo "Ошибка во время добавления комментария.";
    exit;
  }

  mysql_close($link);
  // RETURN TO PREVIOUS PAGE
  header('Location: '.$story);

?>

