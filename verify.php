<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

//Check if we have our $_GET variables for email verification
if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {

    // Verify data
    $email = mysql_escape_string($_GET['email']); // Set email variable
    $hash = mysql_escape_string($_GET['hash']); // Set hash variable
    $search = mysql_query("SELECT email, hash, user_level FROM users WHERE email = ? AND hash = ? AND active='0'") or die(mysql_error()); 
    $match  = mysql_num_rows($search);

    // Prepare a select statement
    $sql = "SELECT email, hash, user_level FROM users WHERE email = ? AND hash = ? AND active='0'";
        
    if ($stmt = mysqli_prepare($link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sS", $param_email, $param_hash);
        
        // Set parameters
        $param_username = $_GET['email'];
        $param_hash = $_GET['hash'];
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);
            
            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {                    
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $email, $hash, $user_level);

                if (mysqli_stmt_fetch($stmt)) {

                    //Change user level with statement
                    // Prepare an insert statement
                    $sql = "INSERT users SET user_level='1' WHERE email = ? AND hash = ? AND user_level='0'";

                    if ($stmt = mysqli_prepare($link, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_hash);
        
                        // Set parameters
                        $param_username = $_GET['email'];
                        $param_hash = $_GET['hash'];
        
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                           
                            // Redirect user to home page
                            header("location: index.php");
                        }
                    }
            } else {
                // No match -> invalid url or account has already been activated.
            }
        } else{
            echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
} else {
    // Invalid approach
}

//Get dynamic header
require_once "header.php"; 
?>

<body>
    <?php require_once "navbar.php"; ?>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>