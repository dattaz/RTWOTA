<?php
function print_tweet($row){
    $rt = " ";
    if ($row['username'] != $row['timelineof']){ //Is the tweet is a RT ?
        $rt = '<p> <img src="style/rt.png" /> RT by <a href="user.php?user='. $row['timelineof'] .'"> @'. $row['timelineof'] . '</a></p>';
    }
    echo '
          <li class="module post original" data-time="' . $row['timestamp'] . '" style="width:100%">
            <div class="post-data original" style="width:100%" >
              <div class="post-photo"><img class="avatar" src="' . $row['avatar'] . '" alt="user-photo"></div>
                <div class="post-info">
                  '. $rt . '
                  <a href="user.php?user='. $row['username'] .'" class="post-info-name open-profile-modal">' . $row['fullname'] . '</a>
                  <span class="post-info-tag"></span>
                  <a class="post-info-time" href="' . $row['url'] . '"><span class="post-info-sent"></span> <span>' . date("D M j G:i:s", $row['timestamp']) . '</span></a>
                </div>
                <p class="post-text" style="padding-top: 10px;">' . $row['content'] .'</p>
                <div class="post-context" style="display: none;"></div>
              </div>
          </li>';
}
?>
