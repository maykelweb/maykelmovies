<?php
//Redirect to homepage if no post id given
if (empty($_GET['id'])) {
    //NO POSTS TO SHOW
    header("location: index.php");
    die();
}

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
    $sql = "INSERT INTO replies (title, content, topic, posted_by) VALUES (?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssii", $param_title, $param_content, $param_topic, $param_posted_by);
        
        // Set parameters
        $param_title = ucwords($title);
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
          //Check URL ID matches a post
          if (mysqli_num_rows($result)==0) {
            //No posts page
            echo "such empty";
          } else {
            //Display Post Information
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
            }
          }
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
              <textarea id="editor" type="textarea" name="reply" rows="10" style="height:100%;" class="form-control" value=""></textarea>
              <span class="invalid-feedback"></span>
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
            <div class="card posts mt-4">
                <div class="card-body"> 
                    <p class="card-subtitle font-italic">by: moviecriticx</p>
                    <p class="card-text mt-3"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus animi quibusdam unde alias quaerat, magni quasi accusantium voluptatum a exercitationem distinctio fugiat, veniam officia qui excepturi. Cum, asperiores eligendi voluptates enim, inventore veniam nihil facere placeat doloremque dolorem itaque et voluptas nobis, commodi quos? Libero quis molestias temporibus consectetur ad?
                    </P>
                    <p class="card-subtitle float-right mt-3">Posted 11/11/2021 15:03pm</p>
                    <a href="">
                        <span class="float-left d-inline-block" style="font-size:1.4em;"> <i class="fas fa-comment mr-2"></i>Reply</span>
                    </a>
                </div>
            </div>
            <div class="card posts ml-5">
                <div class="card-body"> 
                    <p class="card-subtitle font-italic">by: someone99</p>
                    <p class="card-text mt-3"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde quibusdam maiores eligendi laborum libero dolorem officiis tempore placeat nihil voluptate!
                    </P>
                    <p class="card-subtitle float-right mt-3">Posted 11/11/2021 15:15pm</p> <a href="">
                        <span class="float-left d-inline-block" style="font-size:1.4em;"> <i class="fas fa-comment mr-2"></i>Reply</span>
                    </a>
                </div>
            </div>
            <div class="card posts">
                <div class="card-body"> 
                    <p class="card-subtitle font-italic">by: slpaola</p>
                    <p class="card-text mt-3"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit voluptate, atque ipsa eos ad iste sequi quisquam sint officiis fugit nisi id asperiores maiores quis perferendis temporibus quod sapiente sunt. Fugiat, ut! Necessitatibus, rem aliquam!
                    </P>
                    <p class="card-subtitle float-right mt-3">Posted 11/11/2021 16:01pm</p> <a href="">
                        <span class="float-left d-inline-block" style="font-size:1.4em;"> <i class="fas fa-comment mr-2"></i>Reply</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php
require_once("footer.php");
?>