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
        $param_title = ucwords($title);
        $param_content = $content;
        $param_topic = 2;
        $param_posted_by = $_SESSION["id"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
          
        } else{
            echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
        }

        // Close statement
        mysqli_stmt_close($stmt);
        header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
    }
  }
}

//Get dynamic header
require_once "header.php"; 
?>

<body>
    <?php require_once "navbar.php" ?>

    <section>
      <div class="container">
        <div id="createPost" class="mt-5">
          <a id="createPostButton" class="btn btn-primary float-right" onclick="showCreatePost()"> Create New Discussion </a>
          <div style="clear:right;"></div>
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
        <div id="moviePosts" class="mt-1">
          <?php
            $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.topic = 2 ORDER BY posts.time_created DESC";
            $result = mysqli_query($link, $query) or die(mysqli_error($con));
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '
                <a id="posts" href="discuss.php?id='. $row['post_id'] .'">
                <div class="card posts">
                  <div class="card-body">
                    <p class="card-subtitle float-right">'. $row['time_created'] .'</p>
                    <h2 class="card-title">'. $row['title'] .'</h2>
                    <p class="card-subtitle">By: '. $row['username'] .'</p>
                    <p class="card-text">'. mb_strimwidth($row['content'], 0, 130, "...") .'</p>
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