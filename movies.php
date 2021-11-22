<?php
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

// Processing form data when post is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate title
  if(empty(trim($_POST["title"]))){
    $title_err = "Every post needs a title.";    
  } else{
    $title = trim($_POST["title"]);
  }

  //Validate content
  if(empty(trim($_POST["content"]))){
    $content_err = "You must add content before you can submit post.";    
  } else{
    $content = trim($_POST["content"]);
  }

  // Check input errors before inserting in database
  if(empty($content_err) && empty($title_err)){
        
    // Prepare an insert statement
    $sql = "INSERT INTO posts (title, content, topic, posted_by) VALUES (?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssii", $param_title, $param_content, $param_topic, $param_posted_by);
        
        // Set parameters
        $param_title = $title;
        $param_content = $content;
        $param_topic = 1;
        $param_posted_by = $_SESSION["id"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
          
        } else{
            echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
  }

  // Close connection
  mysqli_close($link);
}?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Movies</title>
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
                  <a class="nav-link" href="index.php">Discuss <span></a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="movies.php">Movies <span class="sr-only">(current)</span></a>
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

    <section>
      <div class="container">
        <div id="createPost" class="mt-5">
          <a id="createPostButton" class="btn btn-primary" onclick="showCreatePost()"> Create New Discussion </a>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="createPostForm" method="post" style="display:none;" class="mt-3">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Content</label>
                <textarea type="textarea" name="content" rows="10" style="height:100%;" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $content; ?>"></textarea>
                <span class="invalid-feedback"><?php echo $content_err; ?></span>
            </div>    
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-block" value="Submit">
            </div>
          </form>
        </div>

        <!-- Loop to display all movie posts -->
        <div id="moviePosts" class="mt-3">
          <?php
            $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.topic = 1";
            $result = mysqli_query($link, $query) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                echo '
                <a id="posts" href="discuss.php?id='. $row['post_id'] .'">
                <div class="card">
                  <div class="card-body">
                    <p class="card-subtitle float-right">'. $row['time_created'] .'</p>
                    <h2 class="card-title">'. $row['title'] .'</h2>
                    <p class="card-subtitle">By: '. $row['username'] .'</p>
                    <p class="card-text">'. $row['content'] .'</p>
                  </div>
                </div>
                </a>
                ';
            }
          ?>
        </div>
        <!-- Structure for posts
          <div class="card">
            <div class="card-body">
              <p class="card-subtitle float-right">11/02/21 11:59pm</p>
              <h2 class="card-title">Spiderman 2 </h2>
              <p class="card-subtitle">By: spyderman99</p>
              <p class="card-text">What do you all think of spiderman?</p>
            </div>
          </div>
        -->

      </div>
    </section>

    <script>
      function showCreatePost() {
        const form = document.getElementById('createPostForm');

        <?php if ($_SESSION["loggedin"] == true) { ?>
          if (form.style.display == "none") {
            form.style.display = "block";
          } else {
            form.style.display = "none";
          }
        <?php } else {
          echo "not logged in";
        } ?>
      }
    </script>

<?php
require_once("footer.php");
?>