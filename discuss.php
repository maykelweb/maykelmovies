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

// Processing form data when post is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
}?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    <section>
        <div class="container mt-5">
        <?php //Display posts by URL ID

        //Validate url ID
        $id = preg_replace('/[^0-9]/', '', $_GET['id']);
        $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.post_id = $id";
        if ($result = mysqli_query($link, $query)) {
            //Check URL ID matches a post
            if (mysqli_num_rows($result)==0) {
                echo "such empty";
            } else {
                //Display Post Information
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '
                    <div class="card">
                        <div class="card-body"> 
                            <p class="card-subtitle float-right">'. $row['time_created'] .'</p>
                            <h1>'. $row['title'] .'</h1>
                            <p class="card-subtitle">'. $row['username'] .'</p>
                            <p class="card-text mt-3">'. $row['content'] .'</P>
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