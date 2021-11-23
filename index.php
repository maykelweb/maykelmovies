<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Maykel Movies</title>
</head>
<body>
    <header>
        <nav id="mainNav" class="navbar navbar-expand-md navbar-dark p-4">
            <a class="navbar-brand pr-4" href="index.php">MaykelMovies</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php">Discuss<span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="movies.php">Movies</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="series.php">Series</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="news.php">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">Reviews</a>
                </li>
              </ul>
              <a href="login.php" class="btn btn-light loginButton">  Log In </a>
            </div>
          </nav>
    </header>

    
    <div class="container">
      <div id="search-container">
        <h1 class="text-center mt-5 mb-2 color-primary font-weight-bold"> SEARCH </h1>
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