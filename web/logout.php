<?php

	setcookie('login', '', 0, '/');
	setcookie('passw', '', 0, '/');
	if(isset($_GET['st']) and intval($_GET['st']))  
		header("Location: /story/${_GET['st']}");
	else
		header("Location: /");

?>