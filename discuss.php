<?php require_once "header.php"; ?>

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