<?php


function tmpl_get_story_template($story_id, $story_name, $story_date, $story_text, $story_tags, $next_id, $prev_id, $cnt_stor = 0, $cnt_read = 0, $cnt_book = 0) {
	$tmpl_folder = $_SERVER['DOCUMENT_ROOT'] . "/templates/";
	$t_story_tmpl = file_get_contents($tmpl_folder . "tmpl-story.html");
	$t_tags_pattern = tmpl_get_pattern_by_name('tags', $t_story_tmpl);	
	return str_replace(
		array(
			':{story_number}',
			':{story_title}',
			':{story_date}',
			':{story_text}',
			':{tag_list}',
			':{story_previous}',
			':{story_next}',
			':{prev_disable}',
			':{next_disable}',
			':{stories_total}',
			':{stories_read}',
			':{stories_bookmarked}'
		),
		array(
			$story_id,
			$story_name,
			$story_date,
			$story_text,
			"Tags Here",
			$prev_id,
			$next_id,
			'disabled',
			'disabled',
			$cnt_stor,
			$cnt_read,
			$cnt_book
		),
		$t_story_tmpl
	);
}

function tmpl_get_pattern_by_name($pat_name, $target) {
	preg_match("/<!-- @@tags (.+) -->/i", $target, $result);
	return $result[1];
}