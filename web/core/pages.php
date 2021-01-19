<?php

function display_pages ($link, $pages, $page, $suffix = '') {

	if($pages == 1) return 0;

	$pages_max = 16;
	$pages_nice = 14;

	if($page > 1) { echo "<a href='$link/" . ($page-1) . "$suffix'>←</a> "; }
	if($pages<=$pages_max) {
		for($i=1;$i<=$pages;$i++) {
			if($page==$i) 
				echo "<a id='current'>[$i]</a> ";
			else 
				echo "<a href='$link/$i$suffix'>$i</a> ";
		}
	} else {
		if($page==1) 
			echo "<a id='current'>[1]</a> ";
		else 
			echo "<a href='$link/1$suffix'>1</a> ";
		$min = $page - (int)($pages_nice / 2);
		$max = $min + $pages_nice;
		if($min < 2) {
			$min = 2; 
			$max = $min + $pages_nice; 
		} elseif($max > $pages - 1) {
			$max = $pages - 1; 
			$min = $max - $pages_nice; 
		}

		if($min != 2) { echo "... "; }
		for($i=$min;$i<=$max;$i++) {
			if($page == $i) { echo "<a id='current'>[$i]</a> "; }
			else { echo "<a href='$link/$i$suffix'>$i</a> "; }
		}
		if($max != $pages - 1) { echo "... "; }
		if($page == $pages) 
			echo "<a id='current'>[$pages]</a>";
		else 
			echo "<a href='$link/$pages$suffix'>$pages</a> ";
	}
    if($page < $pages) { echo "<a href='$link/" . ($page+1) . "$suffix'>→</a>"; } 
}


?>
