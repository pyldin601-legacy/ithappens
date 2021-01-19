<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');
require ('core/pages.php');
require ('core/header.inc.php');

$user = $uid['user_id'];

header("Content-type: text/plain; charset=UTF-8");
header('Content-Description: File Transfer');
header('Content-Disposition: Attachment; filename="bookmarks.txt"');

?>


<?php
if($uid) {
	$result = mysql_query("SELECT `a`.* FROM `ithappens` a, `ithappens_bookmark` b WHERE `a`.`story_id`=`b`.`mark_story` and `b`.`mark_user`='$user' order by `a`.`story_id` desc");
    $rows = mysql_num_rows($result);
      while($row = mysql_fetch_assoc($result)) {
        $s_id = $row['story_id'];
        $s_title = $row['story_title'];
        $s_body = html_entity_decode(strip_tags(str_replace("<br>", "\r\n", $row['story_body'])));
		echo $s_id . ': ' . $s_title . "\r\n";
		echo $s_body . "\r\n\r\n* * *\r\n\r\n";
      }
}


db_close();
?>
