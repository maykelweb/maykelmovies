<?php 
// Include config file
require_once "config.php";

// Initialize the session &
if (session_id() == "") {
  session_start();
}
$id = $_SESSION['id'];

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
                <div style="">

                    <?php
                    $query = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE posts.posted_by = $id ORDER BY posts.time_created DESC";
                    $result = mysqli_query($link, $query);
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo '
                            <a id="posts" href="discuss.php?id='. $row['post_id'] .'">
                                <div class="card posts">
                                    <div class="card-body">
                                        <p class="card-subtitle float-right">'. $row['time_created'] .'</p>
                                        <h2 class="card-title">'. $row['title'] .'</h2>
                                        <p class="card-subtitle">By: '. $row['username'] .'</p>
                                        <p class="card-text">'. mb_strimwidth($row['content'], 0, 130, "...") .'</p>
                                    </div>
                                </div>
                            </a>
                        ';
                    }
                    ?>    


                    <div class="card posts">
                        <a href="#">
                            <div class="card-body">
                                <p class="card-subtitle float-right">Posted: an hour ago</p>
                                <div style="clear:right;"></div>
                                <p class="card-subtitle float-right font-italic"> yourname </p>
                                <h2> Lock And Terrible Key </h2>
                                <p class="card-text mt-3"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad
                                    voluptate nisi fugit laboriosam atque pariatur beatae ullam officia unde facere,
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iste, dicta....</P>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>