<?php

return array(
	'db' => array(
		'db_host' => $_ENV['MYSQL_HOSTNAME'],
		'db_user' => $_ENV['MYSQL_USER'],
		'db_pass' => $_ENV['MYSQL_PASSWORD'],
		'db_base' => $_ENV['MYSQL_DATABASE']
	),
	'blog' => array(
		'stories_per_page' => 5,
		'titles_per_column' => 25,
		'user_online_time' => 600
	)
);

?>
