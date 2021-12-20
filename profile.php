<?php 
// Include config file
require_once "config.php";
require_once "functions.php";

// Initialize the session &
if (session_id() == "") {
  session_start();
}

//ID is set to logged in userID automatically
$id = $_SESSION['id'];

//If on someone else's profile, ID variable is replaced to match theirs
if (!empty($_GET['id'])) {
    $id = preg_replace('/[^0-9]/', '', $_GET['id']);
}

//Upload image as profile picture
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
 $name=$_FILES['banner']['name']; 
 $extension=@end(explode('.',$name)); //Get the extension
 $bannerexptype=$extension;
 $filepath="uploads/u". $id . ".jpg";
 move_uploaded_file($_FILES["banner"]["tmp_name"],$filepath);
 header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
}

// Compress image
function compressedImage($source, $path, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $path, $quality);

}

//Check if session ID is still empty
if (empty($id)) {
    // Redirect user to login
    header("location: login.php");     
}

//Get dynamic header
require_once "header.php"; 
?>
 
<body>
    <?php require_once "navbar.php"; 

    //Show any server messages
    if (!empty($_SESSION['msg'])) {
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            '. $_SESSION['msg'] .'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>';
        $_SESSION['msg'] = "";
    }
    ?>

    <!-- Profile Section -->
    <section>
        <div class="container">
            
            <!-- Posts -->
            <div class="row mt-5">
                <div class="" style="width:100%;">
                    <img class="profilePic mb-3" style="width:100px;height:100px;" src="uploads/u<?php echo $id ?>.jpg" onerror="this.onerror=null; this.src='uploads/default.jpg'" alt="Profile picture" />

                    <?php 
                    //Only show if looking at another user's profile.
                    //If user id in session is not equal to profile page id
                    if ($id !== $_SESSION['id']) {
                    ?>
                        <a class="btn p-0"> Follow </a>
                        <a class="btn"> Message </a>
                    <?php 
                    //else show settings if profile is the logged in user's profile.
                    } else {
                    ?>
                        <a class="btn" href="logout.php"> Log Out </a>
                        <a class="btn" href="change-password.php"> Change Password </a>
                        <a class="btn"> Change Timezone </a>
                        <form class="d-inline-block" action="profile.php" method="post" enctype="multipart/form-data">
                            <label for="files" class="btn m-0">Change profile image</label>
                            <input id="files" type="file" name="banner" onchange="this.form.submit()" style="visibility:hidden;"/>
                        </form>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="row mt-3">
                <div class="w-100">

                    <?php
                    $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id LEFT JOIN likes ON likes.like_post_id = posts.post_id WHERE posts.posted_by = $id ORDER BY posts.time_created DESC";
                    displayPosts($query, $link);
                    ?>           
                </div>

            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>