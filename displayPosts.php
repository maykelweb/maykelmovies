<?php
function displayPosts($query, $link){
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '
        <div class="card posts">
          <a id="posts" href="discuss.php?id='. $row['post_id'] .'"></a>
            <div class="card-body">
              <p class="card-subtitle float-right">'. $row['time_created'] .'</p>
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