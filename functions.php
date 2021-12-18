<?php

//Function to display posts, takes a SQL query and database connection
function displayPosts($query, $link){
    //Query the database
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { //Echo all posts in HTML
        echo '
        <div class="card posts">
            <div class="card-body">
              <a href="discuss.php?id='. $row['post_id'] .'" class="d-inline-block">
                <h2 class="post-title">'. $row['title'] .'</h2>
              </a>
              <a class="d-inline-block" href="profile.php?id='. $row['user_id'] .'">
                <p class="card-subtitle post-username d-inline-block"> Discussion by: '. $row['username'] .' '. timePassed($row['time_created']) .'  </p>
              </a>
              <a href="discuss.php?id='. $row['post_id'] .'">
                <p class="card-text">'. mb_strimwidth($row['content'], 0, 130, "...") .'</p>
                </a>
              <span> Replies: 27 </span><span>  </span> 
              <a id="likeButton" class="float-left mr-2" onclick=""> <i class="fas fa-thumbs-up mr-1"></i> <span> 300</span> </a> 
              <span>share</span>
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
      $append = " Minutes ago"; //
      if ($timeDiff > 60) {
        $timeDiff = round(($now - $time)/3600);//Difference in hours
        $append = " Hours ago";
        if ($timeDiff > 60) {
          $timeDiff = round(($now - $time)/86400); //Difference in days
          $append = " Days ago";
        }
      }
      
      //Return time passed
      return $timeDiff . $append;
}

?>