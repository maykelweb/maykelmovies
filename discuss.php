<?php
//Redirect to homepage if no post id given
if (empty($_GET['id'])) {
    //NO POSTS TO SHOW
    header("location: index.php");
    die();
}

$id = $_GET['id'];

// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

// Processing form data when reply is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  //Check User is logged in to reply
  if ($_SESSION["loggedin"] == true) {
    //Validate content
    if(empty(trim($_POST["reply"]))){
      $content_err = "You must add content before you can submit post.";    
    } else {
      $content = trim($_POST["reply"]);
    }

  // Check input errors before inserting in database
  if(empty($content_err)){
        
    // Prepare an insert statement
    $sql = "INSERT INTO replies (reply, reply_user, reply_post, parent_reply_id) VALUES (?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "siii", $param_reply, $param_reply_user, $param_reply_post, $param_parent_reply_id);
        
        // Set parameters
        $param_reply = $content;
        $param_reply_user = $_SESSION["id"];
        $param_reply_post = $id;
        $param_parent_reply_id = null;
        
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

  // Close connection
  }
}?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tiny.cloud/1/rta56yptfl3ip9i4w8su10avcx96ai09g361no65l5y4r04r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
        selector: 'textarea#editor',
        height: 300,
        plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media paste imagetools wordcount'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
    </script>

    <title>Discuss Movies</title>
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

    <section id="post">
        <div class="container">
        <?php //Display posts by URL ID

        //Validate url ID
        $id = preg_replace('/[^0-9]/', '', $_GET['id']);
        $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.post_id = $id";
        if ($result = mysqli_query($link, $query)) {
          
            //Display Post Information
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo '
            <div class="card posts">
                <div class="card-body"> 
                    <h1>'. $row['title'] .' </h1>
                    <p class="card-subtitle font-italic">by: '. $row['username'] .'</p>
                    <p class="card-text mt-3">'. $row['content'] .'</P>
                    <p class="card-subtitle float-right mt-3">Posted '. $row['time_created'] .'</h1>
                </div>
            </div>
            ';
          
        //Error connecting to MySQL
        } else {
          echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
        } ?>

        <div>  
        <!-- Display reply form if user is logged in -->
        <?php if ($_SESSION["loggedin"] == true) { ?>
          <form action="" id="" method="post" class="mt-3">
            <div class="form-group">
              <label>Post a reply</label>
              <textarea id="editor" type="textarea" name="reply" rows="10" style="height:100%;" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value=""></textarea>
              <span class="post-invalid invalid-feedback"><?php echo $content_err; ?></span>
            </div>    
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-block" value="Post">
            </div>
          </form>
          
        <!-- Display log in/sign up button -->
        <?php } else { ?>
          <div class="mt-3">
            <a href="login.php" class="btn btn-primary">Log In</a>
            <a href="register.php" class="btn btn-primary">Sign Up</a>
            <p class="d-inline-block">Login or sign up to leave a comment</p>
          </div>

          <?php } ?>
          
          </div>
        </div>
    </section>

    <section id="thread" class="mb-4">
        <div class="container">
          <?php
            $query = "SELECT * FROM replies LEFT JOIN users ON replies.reply_user = users.user_id WHERE replies.reply_post = $id";
            if ($result = mysqli_query($link, $query)) {
              if (mysqli_num_rows($result)==0) {
              //No Replies
            } else {
              //Display Replies
              while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              echo '
                <div class="card posts">
                  <div class="card-body"> 
                    <p class="card-subtitle font-italic">by: '. $row['username'] .'</p>
                    <p class="card-text mt-3"> '. $row['reply'] .'</p>
                    <p class="card-subtitle float-right mt-3">Posted '. $row['reply_time'] .'</p> 
                    <a href="">
                      <span class="float-left d-inline-block" style="font-size:1.4em;"> <i class="fas fa-comment mr-2"></i>Reply</span>
                    </a>
                  </div>
                </div>
              ';
              }
            }
        
            //Error connecting to MySQL
            } else {
              echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
            }
          ?>
        </div>
    </section>

<?php
require_once("footer.php");
?>