<header>
        <nav id="mainNav" class="navbar navbar-expand-md navbar-dark p-4">
            <a class="navbar-brand pr-4" href="index.php">MaykelMovies</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php">Discuss<span></a>
                </li>
                <li class="nav-item <?php echo ($pageName == "movies.php") ? 'active' : ''; ?>">
                  <a class="nav-link" href="movies.php">Movies</a>
                </li>
                <li class="nav-item <?php echo ($pageName == "series.php") ? 'active' : ''; ?>">
                  <a class="nav-link" href="series.php">Series</a>
                </li>
                <li class="nav-item <?php echo ($pageName == "news.php") ? 'active' : ''; ?>">
                  <a class="nav-link" href="news.php">News</a>
                </li>
                <li class="nav-item <?php echo ($pageName == "reviews.php") ? 'active' : ''; ?>">
                    <a class="nav-link" href="reviews.php">Reviews</a>
                </li>
              </ul>

              <?php //Change login button depending on if user logged in
                echo ($_SESSION['loggedin']) ? 
              '<a href="profile.php" class="d-flex align-items-center">
                <p class="navUsername">'. $_SESSION['username'] .'</p>
                <div class="temporaryProfilePic"></div> 
               </a>' : 
              '<a href="login.php" class="btn btn-light loginButton">  Log In </a>'
               ?>
            </div>
          </nav>
    </header>