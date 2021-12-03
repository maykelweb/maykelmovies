<?php 
// Include config file
require_once "config.php";
require_once "displayPosts.php";

// Initialize the session &
if (session_id() == "") {
  session_start();
}
$id = $_SESSION['id'];

if (!empty($_GET['id'])) {
    $id = preg_replace('/[^0-9]/', '', $_GET['id']);
}


//Get dynamic header
require_once "header.php"; 
?>
 
<body>
    <?php require_once "navbar.php"; 

    //Check user has session ID
    if (empty($id)) {
        //AWESOME ERROR DESIGN GOES HERE
        echo "Sorry you don't seem to be logged in";
        die();
    }?>

    <!-- Profile Section -->
    <section>
        <div class="container">
            
            <!-- Posts -->
            <div class="row mt-5">
                <div class="" style="width:100%;">
                    <img style="width:100px;height:100px;border-radius: 50%;background-color: black;display:block; margin-bottom: 15px;" />
                    <a class="btn p-0"> Follow </a>
                    <a class="btn"> Message </a>
                    <a class="btn" href="logout.php"> Log Out </a>
                    <a class="btn"> Change Timezone </a>
                    <a class="btn"> Change profile picture </a>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="row mt-3">
                <div class="w-100">

                    <?php
                    $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.posted_by = $id ORDER BY posts.time_created DESC";
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