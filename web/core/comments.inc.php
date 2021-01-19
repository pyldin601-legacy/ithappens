<?php

// display comments frame
function comment_frame($userid, $story) {


  // display all comments in this story
  $query = "select `a`.*,`b`.`user_login`,`b`.`user_id`,`b`.`user_fullname` from `ithappens_comments` a, `ithappens_auth` b where `a`.`comment_user`=`b`.`user_id` and `comment_story`=\"$story\" order by `comment_time` asc";
//  echo $query;
  $result = mysql_query($query);
  if(!$result) { echo "mysql_error"; }
  echo '<DIV id="comments">';
  if (mysql_num_rows($result)) {
    echo '<div id="comhead">Отзывы:</div>';
    while($row = mysql_fetch_assoc($result)) {
      echo '<div id="post_frame">';
      if($row['user_fullname'])
        $myname = $row['user_fullname'];
      else
        $myname = $row['user_login'];
      
      echo '<div id="username"><a href="profile.php?user_id='.$row['user_id'].'">'.$myname.'</a>, '.human_date($row['comment_time']).'</div>';
      echo '<div id="post">'.str_replace("\n", "<br>", $row['comment_body']).'</div>';
      echo '</div>';
    }
  }
  
  // if user logged in display 'post' frame
  if ($userid) {
    echo '<div id="comment">';
    echo '<form action="comment.php" method="post">';
    echo 'Оставить отзыв:<br>';
    echo '<input type="hidden" name="story" value="'.$story.'">';
    echo '<textarea input name="comment" maxlength="1024" style="resize: vertical; width: 100%" required></textarea>';
    echo '<br><div id="submit"><input type="submit" value="Сохранить"></div>';
    echo '</form>';
    echo '</div>';
  }

  echo '</DIV>';

}

?>