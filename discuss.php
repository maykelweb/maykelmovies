<?php
//Redirect to homepage if no post id given
if (empty($_GET['id'])) {
    //NO POSTS TO SHOW
    header("location: index.php");
    die();
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$content = $replyTo = "";
$content_err = "";
$id = preg_replace('/[^0-9]/', '', $_GET['id']); //Validate url ID

// Initialize the session
if (session_id() == "")
  session_start();

// Processing form data when reply is submitted if user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["loggedin"] == true) {

    //Validate content
    if (empty(trim($_POST["reply"]))){
      $content_err = "You must add content before you can submit post.";    
    } else {
      $content = trim($_POST["reply"]);
    }

    //Check if user is replying to another user
    if (empty(trim($_POST["replyTo"]))){
      $replyTo = null;
    } else {
      $replyTo = trim($_POST["replyTo"]);
    }

  // Check content is not empty before inserting in database
  if (empty($content_err)) {
        
    // Prepare an insert statement
    $sql = "INSERT INTO replies (reply, reply_user, reply_post, parent_reply_id) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "siii", $param_reply, $param_reply_user, $param_reply_post, $param_parent_reply_id);
        
        // Set parameters
        $param_reply = $content;
        $param_reply_user = $_SESSION["id"];
        $param_reply_post = $id;
        $param_parent_reply_id = $replyTo;
        
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
require_once "header.php"; ?>

<body>
    <?php require_once "navbar.php" ?>
    
    <!-- Display Post Info -->
    <section id="post">
        <div class="container">
        <?php

            //Query to get all replies joined by users from database
            $sql = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.post_id = ?";
        
            if ($stmt = mysqli_prepare($link, $sql)) {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "i", $param_id);
            
              // Set parameters
              $param_id = $id;
            
              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt)){
                $result = $stmt->get_result(); // get the mysqli result

                //Display Replies
                $row= $result->fetch_assoc(); // fetch data   
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
                
              } else{
                echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
              }

            // Close statement
            mysqli_stmt_close($stmt);
            }
        ?>

        <div>  
        <!-- Display reply form if user is logged in -->
        <?php if ($_SESSION["loggedin"] == true) { ?>
          <form action="" id="" method="post" class="mt-3">
            <div class="form-group">
              <label>Post a reply</label>
              <textarea type="textarea" name="reply" rows="10" style="height:100%;" class="editor form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value=""></textarea>
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
    
    <!-- Display Replies -->
    <section id="thread" class="mb-4">
        <div class="container">
          <?php
            //Query to get all replies joined by users from database
            $sql = "SELECT * FROM replies LEFT JOIN users ON replies.reply_user = users.user_id WHERE replies.reply_post = ? ORDER BY replies.parent_reply_id ASC";
        
            if ($stmt = mysqli_prepare($link, $sql)) {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "i", $param_id);
            
              // Set parameters
              $param_id = $id;
            
              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt)){
                $result = $stmt->get_result(); // get the mysqli result

                //Declaring empty js variables
                ?><script>let parentId, postsId, posts;</script><?php 

                //Display Replies
                $count = 0; //Count to display flex order

                //Loop through all variables in SQL array
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                  if (empty($row['parent_reply_id'])) { //Check post has no parent reply
                    echo '
                    <div class="card posts" style="order:'.$count.'">
                    <div class="card-body"> 
                      <a href="profile.php?id='. $row['user_id'] .'">
                        <p class="card-subtitle font-italic">by: '. $row['username'] .'</p>
                      </a>
                      <p class="card-text mt-3"> '. $row['reply'] .'</p>
                      <p class="card-subtitle float-right mt-3">Posted '. $row['reply_time'] .'</p> 
                      <a id="u'. $row['reply_id'] .'" class="replyButton">
                        <span class="float-left d-inline-block" style="font-size:1.4em;"> <i class="fas fa-comment mr-2"></i>Reply</span>
                      </a>

                      <!-- Debug -->
                      <p> Reply ID: '. $row['reply_id'] .'</p>
                      <p> Parent Reply ID: '. $row['parent_reply_id'] .'</p>
                      <span type="hidden" id="reply_id" value="'. $row['reply_id'] .'">
                    </div>
                    </div>
                    ';  
                  //If has a parent reply, display indented below parent
                  } else {
                    ?>
                    <script>
                      parentId = <?php echo $row['parent_reply_id']; ?>;
                      posts = document.querySelectorAll('.posts');
                      postsId = document.querySelectorAll('#reply_id');
                      for (const post of postsId) {
                        if (post.getAttribute("value") == parentId) {
                          console.log("match");
                        }
                      }
                    </script>
                    <?php
                  }

                $count++;
                }  
              } else{
                echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
              }

            // Close statement
            mysqli_stmt_close($stmt);
            }
          ?>

          <!-- Reply Form -->
          <form action="" id="replyForm" method="post" class="mt-3">
            <div class="form-group">
              <label>Post a reply</label>
              <textarea type="textarea" name="reply" rows="10" style="height:100%;" class="editor form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value=""></textarea>
              <span class="post-invalid invalid-feedback"><?php echo $content_err; ?></span>
              <input type="hidden" id="replyTo" name="replyTo" value="">
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
        let form = document.getElementById('replyForm')
        let formReply = document.getElementById('replyTo')

        for (var i = 0; i < replyButtons.length; i++) {
          replyButtons[i].addEventListener('click', (e) => {
            //Going back 3 elements to get parent div
            parentDiv = e.target.parentElement;
            userID = parentDiv.id.substring(1); //Remove first U letter from ID
            for (var i = 0; i < 2; i++) {
              parentDiv = parentDiv.parentElement;
            }
            
            //Show form and set reply value
            formReply.value = userID;
            form.style.cssText = parentDiv.style.cssText;
            form.style.height = 'auto';
            form.style.display = 'block';
            form.scrollIntoView({behavior: 'smooth'});
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

  #replyForm {
    height: 0;
    display:none;
    
    transition: height 2s ease-in;
  }
</style>