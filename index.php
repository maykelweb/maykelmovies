<?php 
// Include config file
require_once "config.php";
require_once "functions.php";

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
        Your account is still not verified to post yet. Click <a href="resendConfirmation.php"> here </a> to resend email.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
    } else if ($_SESSION['user_level'] == "303") {//user level 303 is code for user currently verifying
      //verification email has been sent
      echo '
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Success! Your verification email has been sent again. Please wait up to 15 minutes and check your spam folder. If you still don\'t see it contact us for support. 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
      $_SESSION['user_level'] = "0"; //Set 0 so verify message will show again if not verified
    }
    ?>
    
    <div class="container">

    <!-- Top Functions -->
    <section id="topFunctions">
      <div class="container d-flex mt-5">
        <button class="btn text-white mr-1">Top Posts</button>
        <button class="btn text-white">Newest</button>
        <button id="createPostButton" class="btn btn-primary ml-auto" onclick="showCreatePost()"> Create New Discussion </button>
        </div>
    </section>

    <!-- Top Posts -->
    <section id="topPosts" class="mt-1">
      <div class="container">

      <?php
        //Display Top 3 Posts
        $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id LEFT JOIN likes ON likes.like_post_id = posts.post_id ORDER BY likes DESC LIMIT 3";
        displayPosts($query, $link);
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