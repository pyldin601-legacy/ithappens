
<?php

require ('core/settings.inc.php');
require ('core/auth.inc.php');

$story = $_GET['story'];

if(!isset($story)) {
	echo "Не указан номер истории!";
    exit;
} 

// DOES STORY BOOKMARKED
if ($uid) {
    $result = mysql_query("select * from `ithappens_bookmark` where `mark_story`='$story' and `mark_user`='${uid['user_id']}'");
    $rows = mysql_num_rows($result);
    if($rows) {
		$result = mysql_query("delete from `ithappens_bookmark` where `mark_story`='$story' and `mark_user`='${uid['user_id']}'");
    } else {
		$result = mysql_query("insert into `ithappens_bookmark` (`mark_story`, `mark_user`) values ('$story', '${uid['user_id']}');");
	}
}

db_close();
header('Location: /story/' . $story);

