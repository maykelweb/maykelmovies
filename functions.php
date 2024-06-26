<?php

//Function to display posts, takes a SQL query and database connection
function displayPosts($query, $link){
    //Query the database
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { //Echo all posts in HTML
        echo '
        <div class="card posts mb-2 mt-2">
            <div class="card-body">
              <a href="discuss.php?id='. $row['post_id'] .'" class="d-inline-block">
                <h2 class="post-title">'. $row['title'] .'</h2>
              </a>
              <a class="d-inline-block" href="profile.php?id='. $row['user_id'] .'">
                <p class="card-subtitle post-username d-inline-block"> Discussion by: '. $row['username'] .' '. timePassed($row['time_created']) .'  </p>
              </a>
              <a class="card-text d-block" href="discuss.php?id='. $row['post_id'] .'">
                '. mb_strimwidth($row['content'], 0, 130, "...") .'
              </a>
              <div class="mt-3">
                <a id="likeButton" class="mr-2 '.($row['like_user_id'] == $_SESSION['id'] ? "postLiked" : "").'" onclick="likePost(this, '.$row['post_id'].', '.$_SESSION['id'].')"> 
                  <i class="fas fa-thumbs-up mr-1"></i> <span>'.$row['likes'].'</span> 
                </a>
                <span> '. $row['replies'] .' Replies </span>
                <a class="post-share ml-2" onclick="share()"> Share </a>
              </div>
            </div>
        </div>
        ';
    }
}

//Returns time passed since datetime parameter to now
function timePassed($time) {
      //Format date
      $date = new DateTime($time); //Post time created
      $current_time = new DateTime(); //Now
      $now = $current_time->getTimestamp(); //Convert both dates to timestamps
      $time = $date->getTimestamp();
      
      //Calculate minutes/hours/days since post and appends text
      $timeDiff = round(($now - $time)/60); //Difference in minutes
      ($timeDiff == 1) ? $append = " minute ago" : $append = " minutes ago";
      if ($timeDiff > 60) {
        $timeDiff = round(($now - $time)/3600);//Difference in hours
        ($timeDiff == 1) ? $append = " hour ago" : $append = " hours ago";
        if ($timeDiff > 24) {
          $timeDiff = round(($now - $time)/86400); //Difference in days
          ($timeDiff == 1) ? $append = " day ago" : $append = " days ago";
        }
      }
      
      //Return time passed
      return $timeDiff . $append;
}

?>