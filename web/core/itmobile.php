<?php

class itmobile {
	private $config, $pdo, $auth;
	function __construct() {
		$this->config = require($_SERVER['DOCUMENT_ROOT'] . '/config.php');
		$this->pdo = new PDO (
			sprintf('mysql:host=%s;dbname=%s', $this->config['db']['db_host'], $this->config['db']['db_base']), 
			$this->config['db']['db_user'], 
			$this->config['db']['db_pass']
		);
		$this->pdo->query("SET NAMES 'utf8'");
	}
	function num_stories() {
		$q = $this->pdo->prepare("SELECT COUNT(*) FROM `ithappens`");
		$q->execute();
		$data = $q->fetch(PDO::FETCH_NUM);
		return $data[0];
	}
	function get_story($id) {
		$q = $this->pdo->prepare("SELECT * FROM `ithappens` WHERE `story_id` = ? LIMIT 1");
		$q->execute(array($id));
		if( $data = $q->fetchAll(PDO::FETCH_ASSOC) ) {
			return $data[0];
		} else {
			return false;
		}
	}
	function get_next_story($id) {
		//$res = select `story_id` from `ithappens` where `story_id`>'${default_story}' order by `story_id` limit 1
	}
}

function storyParse($story) {
	$screening = false;
	$story = preg_replace('/(<br>)+$/', '', $story);
	$lines = explode("<br>", $story);
	foreach ($lines as &$value) {
		// crosslinking story
		$value = preg_replace('/(^|\s)#(\d+)/', ">#$2</a>", $value);
		$value = preg_replace('/http\:\/\/ithappens\.ru\/story\/(\d+)/', "/story/$1", $value);
		if (strlen($value)) {
			$value = "<p>$value</p>";
		} else {
			// empty line
			$value = "<br>";
		}
	}
	return implode("", $lines);
}



?>
