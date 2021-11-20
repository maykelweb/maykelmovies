<?php
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
}
?>
 
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