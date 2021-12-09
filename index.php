<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

//Get dynamic header
require_once "header.php"; 
?>

<body>
    <?php require_once "navbar.php";
    //Show verification required message if user is not verified
    if ($_SESSION['user_level'] == "0") {
      echo '
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        Your account is still not verified. To resend verification email please click <a href="confirmation.php"> here </a> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
    }
    ?>
    
    <div class="container">
      <div id="search-container">
        <h1 class="text-center mt-5 mb-2 color-primary font-weight-bold"> search </h1>
        <div class="search-box">
          <input type="text">
        </div>
      </div>
    </div>

    <!-- Top Functions -->
    <section id="topFunctions">
      <div class="container d-flex">
        <button class="btn text-white mr-1">Top Posts</button>
        <button class="btn text-white">Newest</button>
        <h2 class="text-center banner mt-5 color-primary d-inline-block ml-auto">JOIN THE DISCUSSION</h2>
        <button id="createPostButton" class="btn btn-primary ml-auto" onclick="showCreatePost()"> Create New Discussion </button>
        </div>
    </section>

    <!-- Top Posts -->
    <section id="topPosts" class="mt-1">
      <div class="container">

      <?php
        //Display Top 3 Posts
        $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id ORDER BY likes DESC LIMIT 3";
        if ($result = mysqli_query($link, $query)) {
          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '
            <div class="card posts">
            <a href="discuss.php?id='. $row['post_id'] .'">
              <div class="card-body"> 
                <p class="card-subtitle float-right">Posted: '. $row['time_created'] .'</p>
                <div style="clear:right;"></div>
                <p class="card-subtitle float-right font-italic"> '. $row['username'] .' </p>
                <h2 class="card-title"> '. $row['title'] .'</h2>
                <p class="card-text mt-3">'. mb_strimwidth($row['content'], 0, 100, '...') .'</P>
              </div>
            </a>
            </div>
            ';
          }
        //Error connecting to MySQL
        } else {
            echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
        }        
       ?>
        
        <a class="continue" href="#Action"> Continue Reading </a>
      </div>
    </section>

    <section id="Action">
      <div style="height:100vh;">

      </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>