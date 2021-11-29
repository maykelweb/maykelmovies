<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

//Get dynamic header
require_once "header.php"; 
?>
 
<body>
    <?php require_once "navbar.php" ?>

    <!-- Profile Section -->
    <section>
        <div class="container">
            
            <!-- Posts -->
            <div class="row mt-5">
                <div class="" style="width:100%;">
                    <img style="width:100px;height:100px;border-radius: 50%;background-color: black;display:block; margin-bottom: 15px;" />
                    <a class="btn p-0"> Follow </a>
                    <a class="btn"> Message </a>
                    <a class="btn"> Log Out </a>
                    <a class="btn"> Change Timezone </a>
                    <a class="btn"> Change profile picture </a>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="row mt-3">
                <div style="">

                    <div class="card posts">
                        <a href="#">
                            <div class="card-body">
                                <p class="card-subtitle float-right">Posted: an hour ago</p>
                                <div style="clear:right;"></div>
                                <p class="card-subtitle float-right font-italic"> AvengersFanatic69 </p>
                                <h2> Avengers Infinity Stone Episode 8 Discussion </h2>
                                <p class="card-text mt-3"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad
                                    voluptate nisi fugit laboriosam atque pariatur beatae ullam officia unde facere?...
                                </P>
                            </div>
                        </a>
                    </div>
                    <div class="card posts">
                        <a href="#">
                            <div class="card-body">
                                <p class="card-subtitle float-right">Posted: an hour ago</p>
                                <div style="clear:right;"></div>
                                <p class="card-subtitle float-right font-italic"> Anonymousity2 </p>
                                <h2> Next by Nicholas Cage </h2>
                                <p class="card-text mt-3"> Lorem ipsum dolor sit amet consectetur adipisicing...</P>
                            </div>
                        </a>
                    </div>
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