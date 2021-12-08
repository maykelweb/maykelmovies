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
    
    <section class="gallery-box mt-5">
      <div class="container">
        <div class="images d-flex">
          <div class="reviews" style="height:550px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:150px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
          <div class="reviews" style="height:250px;background:black; margin:1%;"></div>
      </div>
    </section>

    <div class="box">
        <div>One</div>
        <div>Two</div>
        <div>Three</div>
        <div>Four</div>
        <div>Five</div>
        <div>Six</div>
        <div>Seven</div>
        <div>Eight</div>
        <div>Nine</div>
        <div>Ten</div>
      </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

<style>
.reviews {
  width: 30%;
  align-self: flex-start;
}

.images {
  flex-flow: column wrap;
  height: 1300px;
  width: 100%;
  gap: 10px;
  gap: 10px 20px; /* row-gap column gap */
  row-gap: 10px;
  column-gap: 20px;
}

.box {
        height: 300px;
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
    }
    .box>* {
        flex: 1 1 80px;
        background: blue;
        margin: 1%;
    }
</style>