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

  }
}
require_once "header.php"; ?>

<body>
    <?php require_once "navbar.php" ?>

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
                    <a href="profile.php?id='. $row['user_id'] .'">
                      <p class="card-subtitle font-italic">by: '. $row['username'] .'</p>
                    </a>
                    <p class="card-text mt-3">'. $row['content'] .'</p>
                    <p class="card-subtitle float-right mt-3">Posted '. $row['time_created'] .'</h1>
                </div>
            </div>
            ';
          
        
        } else {
          //Error connecting to MySQL
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
    
    <!-- This is the replies part -->
    <section id="thread" class="mb-4">
        <div class="container">
          <?php
            $query = "SELECT * FROM replies LEFT JOIN users ON replies.reply_user = users.user_id WHERE replies.reply_post = $id";
            if ($result = mysqli_query($link, $query)) {
              if (mysqli_num_rows($result)==0) {
              //No Replies
            } else {
              //Display Replies
              $count = 0;
              while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              echo '
                <div class="card posts" style="order:'.$count.'">
                  <div class="card-body"> 
                    <a href="profile.php?id='. $row['user_id'] .'">
                      <p class="card-subtitle font-italic">by: '. $row['username'] .'</p>
                    </a>
                    <p class="card-text mt-3"> '. $row['reply'] .'</p>
                    <p class="card-subtitle float-right mt-3">Posted '. $row['reply_time'] .'</p> 
                    <a id="user'. $row['user_id'] .'" class="replyButton">
                      <span class="float-left d-inline-block" style="font-size:1.4em;"> <i class="fas fa-comment mr-2"></i>Reply</span>
                    </a>
                  </div>
                </div>
              '; $count++;
              }
            }
            
            } else {
              //Error connecting to MySQL
              echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
            }
          ?>

          <!-- Reply Form -->
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
        </div>
    </section>

    <script>
      window.onload = () => {
        //Add onclick event for all reply buttons
        let replyButtons = document.querySelectorAll('.replyButton')

        for (var i = 0; i < replyButtons.length; i++) {
          replyButtons[i].addEventListener('click', (e) => {
            //Going back 3 elements to get parent div
            parentDiv = e.target.parentElement;
            for (var i = 0; i < 2; i++) {
              parentDiv = parentDiv.parentElement;
            }
            
            /*Create form to insert
            var replyForm = '
            <form action="" id="" method="post" class="mt-3">' +
            '<div class="form-group">' +
              '<label>Post a reply</label>' +
              '<textarea id="editor" type="textarea" name="reply" rows="10" style="height:100%;" class="form-control ' + '<' + '?php echo (!empty($content_err)) ? "is-invalid" : ""; ?>" value=""></textarea>' +
              '<span class="post-invalid invalid-feedback">' + '<' + '?php echo $content_err; ?></span>' +
            '</div>' + 
            '<div class="form-group">' +
              '<input type="submit" class="btn btn-primary btn-block" value="Post">' +
            '</div>' +
            '</form>';

            var testForm = 
            '<form action="" id="" method="post" class="mt-3">' +
            '<div class="form-group">' +
              '<label>Post a reply</label>' +
              '<textarea id="editor" type="textarea" name="reply" rows="10" style="height:100%;" class="form-control"> </textarea>' + 
            '</div>' +
            '<div class="form-group">' +
              '<input type="submit" class="btn btn-primary btn-block" value="Post">' +
            '</div>' +
            '</form>';
            
            parentDiv.insertAdjacentHTML('afterend', testForm);
            */
          })
        }
      }
    </script>

<?php
require_once("footer.php");
?>

<style>
  #thread .container {
    display: flex;
    flex-direction: column;
  }
</style>