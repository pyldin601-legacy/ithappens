<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');

if($uid) {
	if($_POST['text'])
		$text = mysql_real_escape_string($_POST['text']);

	if($_POST['story'])
		$story = mysql_real_escape_string($_POST['story']);

	if($text && $story)
		mysql_query("insert into `ithappens_comments` (`user_id`, `comment_body`, `comment_story`) values ('${uid['user_id']}', '$text', '$story')");

}

db_close();

header("Location: /");

?>