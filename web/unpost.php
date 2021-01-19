<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');

if($uid) {
	if($_GET['c'])
		$comment = mysql_real_escape_string($_GET['c']);

	if($comment)
		mysql_query("delete from `ithappens_comments` where `user_id` = '${uid['user_id']}' and `comment_id` = '$comment'");
}

db_close();

header("Location: /");

?>