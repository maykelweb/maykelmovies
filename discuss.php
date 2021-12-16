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
            $sql = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id LEFT JOIN likes ON likes.like_post_id = posts.post_id WHERE posts.post_id = ?";
        
            if ($stmt = mysqli_prepare($link, $sql)) {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "i", $param_id);
            
              // Set parameters
              $param_id = $id;
            
              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt)){
                $result = $stmt->get_result(); // get the mysqli result

                //Set variables
                $postLiked = "";
                $likeCount = 0;

                //Loop through likes
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                  //If user id has liked the post, set postLiked variable
                  if ($row['like_user_id'] == $_SESSION['id']) {
                    $postLiked = "postLiked"; 
                  }

                  //Increase like count
                  if (!empty($row['like_id'])) {
                    $likeCount = $likeCount + 1;
                  }
                  if ($likeCount == 0) $likeCount = ""; //Stop showing 0 on likes

                  //Set last loop as post array to display below
                  $post = $row;
                }

                //Display post html
                echo '
                  <div class="card posts">
                      <div class="card-body">
                          <div class="text-center d-inline-block">
                            <img class="profilePic mb-3" style="width:100px;height:100px;" src="uploads/u'. $post['user_id'] .'.jpg" onerror="this.onerror=null; this.src=\'uploads/default.jpg\'" alt="Profile picture" />
                            <a href="profile.php?id='. $post['user_id'] .'">
                              <p class="card-subtitle font-italic font-weight-bold post-username">'. $post['username'] . '</p>
                            </a>
                          </div>
                          <h1 class="mt-3">'. $post['title'] .' </h1>
                          <div class="card-text mt-3 mb-5">'. $post['content'] .'</div>
                          <a id="likeButton" class="float-left '.$postLiked.'" onclick="likePost(this, '.$post['post_id'].', '.$_SESSION['id'].')"> <i class="fas fa-thumbs-up mr-1"></i> <span>'.$likeCount.'</span> </a>
                          <p class="card-subtitle float-right post-time">'. $post['time_created'] .'</h1>
                      </div>
                  </div>
                ';
                
              } else {
                echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
              }

            // Close statement
            mysqli_stmt_close($stmt);
            }
        ?>

        <div>  
        <?php if ($_SESSION["loggedin"] == true) { ?>
        <!-- Display reply form if user is logged in -->
          <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'])?>" id="" method="post" class="mt-3">
            <div class="form-group">
              <label>Post a reply</label>
              <textarea type="textarea" name="reply" rows="10" style="height:100%;" class="editor form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value=""></textarea>
              <span class="post-invalid invalid-feedback"><?php echo $content_err; ?></span>
            </div>    
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-block" value="Post">
            </div>
          </form>
          
        <?php } else { ?>
          <!-- Display log in/sign up button -->
          <div class="mt-3 mb-4">
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
        <div class="container d-flex flex-column">
          <?php
            //Query to get all replies joined by users from database
            $sql = "SELECT * FROM replies LEFT JOIN users ON replies.reply_user = users.user_id LEFT JOIN likes ON likes.like_reply_id = replies.reply_id WHERE replies.reply_post = ? ORDER BY replies.parent_reply_id ASC";
        
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
                
                $count = 0; //Count to display flex order

                //Loop through all variables in SQL array and echo reply html
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                  if (empty($row['parent_reply_id'])) { //Check post has no parent reply

                    //Display Replies HTML
                    echo '
                    <div class="post-container" style="order:'.$count.'">
                    <div class="card posts">
                      <div class="card-body"> 
                        <a onClick="togglePost(this)" class="hide-post">  [ - ] </a>
                        <div class="text-center d-inline-block">
                            <img class="profilePic mb-3"  src="uploads/u'. $row['user_id'] .'.jpg" onerror="this.onerror=null; this.src=\'uploads/default.jpg\'"  alt="Profile picture" />
                            <a href="profile.php?id='. $row['user_id'] .'">
                              <p class="card-subtitle font-italic font-weight-bold">'. $row['username'] . '</p>
                            </a>
                        </div>
                        <div class="card-text mt-3">'.$row['reply'] .' </div>
                        <a id="likeButton" class="float-left mr-2 '.($row['like_user_id'] == $_SESSION['id'] ? "postLiked" : "").'" onclick="likeReply(this, '.$row['reply_id'].', '.$_SESSION['id'].', '.$id.')"> <i class="fas fa-thumbs-up mr-1"></i> <span>'.($row['reply_likes'] == 0 ? "" : $row['reply_likes']).'</span> </a>
                        <p class="card-subtitle float-right mt-3 post-time">'. $row['reply_time'] .'</p>';
                        
                        if ($_SESSION["loggedin"] == true) { //Only show reply button if logged in
                          echo 
                            '<a id="u'. $row['reply_id'] .'" class="replyButton" onclick="showReplyForm(this, \''. $row['username'] .'\')">
                            <span class="float-left d-inline-block"> <i class="fas fa-comment mr-2"></i>Reply</span>
                            </a>';
                        }
                        
                        echo '
                        <span type="hidden" id="reply_id" value="'. $row['reply_id'] .'">
                      </div>
                    </div>
                    </div>
                    ';  
                        //'.($row['like_user_id'] == $_SESSION['id'] ? "postLiked" : "").'
                        
                  //If reply has a parent reply, display indented below parent
                  } else { ?>
                    <script>
                      parentId = <?php echo $row['parent_reply_id']; ?>; //Parent Id for post being displayed
                      postsId = document.querySelectorAll('#reply_id'); //Array of all posts id
                      for (const postId of postsId) {
                        //Check if parent id matches post id
                        if (postId.getAttribute("value") == parentId) {
                          post = postId.parentElement.parentElement.parentElement; //Get post-container div

                          //Create new card div
                          var card = document.createElement('div'); 
                          card.style.cssText = post.style.cssText; //Set same flex order as parent
                          card.setAttribute('class', 'post-container'); //Set classes
                          
                          //set margin left to integer and remove percentage mark
                          let parentIndent = parseInt(post.style.marginLeft.replace(/[^0-9]/g, ''));
                          if (isNaN(parentIndent)) { //If no margin make it 0
                            parentIndent = 0;
                          }
                          card.style.marginLeft = "3%"; //Add +3% of previews parent indent to reply

                          //Create reply html for div
                          card.innerHTML = 
                            '<div class="card posts">' +
                            '<div class="card-body">' + 
                              '<a onClick="togglePost(this)" class="hide-post"> [ - ] </a>' +
                              '<div class="text-center d-inline-block">' +
                                '<img class="profilePic mb-3"  src="uploads/u<?php echo $row['user_id'] ?>.jpg" onerror="this.onerror=null; this.src=\'uploads/default.jpg\'" alt="Profile picture" />' +
                                '<a href="profile.php?id=<?php echo $row['user_id'] ?>">' +
                                  '<p class="card-subtitle font-italic font-weight-bold"> <?php echo $row['username'] ?> </p>' +
                                '</a>' +
                              '</div>' +
                              '<div class="card-text mt-3"> <?php echo $row['reply'] ?></div>' +

                              '<a id="likeButton" class="float-left mr-2 <?php echo ($row["like_user_id"] == $_SESSION["id"] ? "postLiked" : "")?>" onclick="likeReply(this, <?php echo $row["reply_id"] . ", " . $_SESSION["id"] . ", " . $id ?>)"> <i class="fas fa-thumbs-up mr-1"></i> <span> <?php echo ($row["reply_likes"] == 0 ? "" : $row["reply_likes"])?></span> </a>' +

                              '<p class="card-subtitle float-right mt-3 post-time"> <?php echo $row['reply_time'] ?></p>' +
                              <?php if ($_SESSION["loggedin"] == true) { //Only show reply button if logged in ?>
                              '<a id="u<?php echo $row['reply_id'] ?>" class="replyButton" onclick="showReplyForm(this, \'<?php echo $row['username']?>\')">' +
                                '<span class="float-left d-inline-block" style="font-size:0.9em;"> <i class="fas fa-comment mr-2"></i>Reply</span>' +
                              '</a>' +
                              <?php } ?>
                              '<span type="hidden" id="reply_id" value="<?php echo $row['reply_id'] ?>">' +
                            '</div>' +
                            '</div>'
                          
                          //Append reply html to parent post
                          post.appendChild(card);
                        }
                      }
                    </script>
                    <?php
                  }

                $count++; //Increase count of flex order
                }  
              
              //Problem connecting to mysql database
              } else{
                echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
              }

            // Close statement
            mysqli_stmt_close($stmt);
            }
          ?>

          <?php if ($_SESSION["loggedin"] == true) { //Only show form if logged in ?>
          <!-- Reply Form  -->
          <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" id="replyForm" method="post" class="mt-3">
            <div class="form-group">
              <div class="d-flex flex-row">
                <label class="mt-auto">Post a reply</label>
                <button id="closeReply" onclick="closeReplyForm()" type="button" title="close"> x </button>
              </div>
              <textarea type="textarea" name="reply" rows="10" style="height:100%;" class="editor form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value=""></textarea>
              <span class="post-invalid invalid-feedback"><?php echo $content_err; ?></span>
              <input type="hidden" id="replyTo" name="replyTo" value="">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-block" value="Post">
            </div>
          </form>
          <?php } ?>
        </div>
    </section>

<?php
require_once("footer.php");
?>