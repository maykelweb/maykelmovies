<?php
// Include config file
require_once "config.php";
require_once "displayPosts.php";

// Initialize the session
if (session_id() == "")
  session_start();

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

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
  if (empty($content_err) && empty($title_err)) {
        
    // Prepare an insert statement
    $sql = "INSERT INTO posts (title, content, topic, posted_by) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssii", $param_title, $param_content, $param_topic, $param_posted_by);
        
        // Set parameters
        $param_title = ucwords($title);
        $param_content = $content;
        $param_topic = 1;
        $param_posted_by = $_SESSION["id"];
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)){
          
        } else {
            echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
        }

        // Close statement
        mysqli_stmt_close($stmt);
        header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
    }
  }
}

require_once "header.php";
?>
<body>
    <?php require_once "navbar.php" ?>

    <section>
      <div class="container">

        <!-- Create Post Button -->
        <div id="createPost" class="mt-5">
          <button id="createPostButton" class="btn btn-primary float-right" data-toggle="modal" data-target="#newPost"> Create New Discussion </button>
          <div style="clear:right;"></div>
          
          <!-- Create Post Modal -->
          <div class="modal fade" id="newPost" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Submit a post</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                 <!-- Create Post Form -->
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="createPostForm" method="post">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                      <span class="invalid-feedback"><?php echo $title_err; ?></span>
                    </div>    
                    <div class="form-group">
                      <label>Content</label>
                      <textarea type="textarea" name="content" rows="10" style="height:100%;" class="form-control editor <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $content; ?>"></textarea>
                      <span class="invalid-feedback"><?php echo $content_err; ?></span>
                    </div>    
                    <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Post">
                  </div>
                </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Loop to display all movie posts -->
        <div id="moviePosts" class="mt-1">
          <?php
            //Selecting all posts and users connected to those posts from database and ordering by time descending
            $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.topic = 1 ORDER BY posts.time_created DESC";
            displayPosts($query, $link);
          ?>
        </div>

      </div>
    </section>

    <script>
      window.onload () = ()

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