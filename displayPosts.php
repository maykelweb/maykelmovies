<?php
function displayPosts($query, $link){
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

      //Format timezone date
      $timezone = "Europe/London"; //$_SESSION['timezone']
      $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['time_created']);
      $date->setTimeZone(new DateTimeZone($timezone));

      ?>
      <script>
        //Get local timezone by javascript
        //console.log(Intl.DateTimeFormat().resolvedOptions().timeZone)
      </script>
      <?php

      /* List all possible time zones 
      $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
      forEach ($tzlist as $tz) {
        echo $tz;
      }
      */

        echo '
        <div class="card posts">
          <a id="posts" href="discuss.php?id='. $row['post_id'] .'"></a>
            <div class="card-body">
              <p class="card-subtitle float-right">'. $date->format('d M Y \a\t H:i') .'</p>
              <h2 class="card-title">'. $row['title'] .'</h2>
              <a class="user-link d-inline-block" href="profile.php?id='. $row['user_id'] .'">
                <p class="card-subtitle">By: '. $row['username'] .'</p>
              </a>
              <p class="card-text">'. mb_strimwidth($row['content'], 0, 130, "...") .'</p>
            </div>
        </div>
        ';
    }
}
 
?>