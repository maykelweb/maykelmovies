<?php 
//Check for search queries
if (empty(trim($_GET["query"]))) {
    //Send back to homepage if no query found
    header("location: index.php");
}

// Include config file
require_once "config.php";
require_once "functions.php";

//Initialize variables
$query = trim($_GET["query"]);

// Initialize the session
if (session_id() == "")
  session_start();

//Get dynamic header
require_once "header.php"; 
?>

<body>
    <?php require_once "navbar.php"; ?>

    <section>
        <div class="container mt-5">
            

            <?php
            //Query the search result and display matching posts
            $sql = "SELECT * FROM posts WHERE (`title` LIKE '%".$query."%')"; // OR (`content` LIKE '%".$query."%')
            $result = mysqli_query($link, $sql);
            
            //Check if no matching posts found
            if ($result->num_rows == 0) {
            ?> 
                <h2> Sorry we couldn't find anything that matches your search </h2>
            <?php
            }

            //Display all matching posts
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { 
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
            ?>

        </div>
    </section>
    
<?php require_once "footer.php"; ?>

    