<header>
        <nav id="mainNav" class="navbar navbar-expand-xl navbar-dark p-4">
            <a class="navbar-brand pr-4 mr-auto" href="index.php">MaykelMovies</a>

            <div id="nav-search" class="d-xl-none">
              <input type="text" class="searchBox" placeholder="Search...">
              <button type="submit" class="searchButton">
                <i class="fa fa-search"></i>
              </button>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-custom-toggler"></span>
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

              <input type="text" class="searchBox" placeholder="Search...">
                <button type="submit" class="searchButton">
                <i class="fa fa-search"></i>
              </button>

              <?php //Change login button depending on if user logged in
                echo ($_SESSION['loggedin']) ? 
              '<a href="profile.php" class="d-flex align-items-center">
                <img class="profilePic" src="uploads/u'. $_SESSION['id'] .'.jpg" onerror="this.onerror=null; if (this.src != \'Default.jpg\') this.src=\'uploads/default.jpg\'" alt="Profile picture"></img> 
               </a>' : 
              '<a href="login.php" class="btn btn-light loginButton">  Log In </a>'
               ?>
            </div>
          </nav>

          
          <div id="actionBar">
            <input type="text" class="searchBox" placeholder="Search...">
            <button type="submit" class="searchButton">
              <i class="fa fa-search"></i>
            </button>

            <button id="createPostButton" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#newPost"> 
              Create Post
            </button>
          </div>
    </header>