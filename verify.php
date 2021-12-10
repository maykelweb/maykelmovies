<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

// Define variables and initialize with empty values
$$email = $hash = "error";
$email_err = $hash_error = "error";

//Check if we have our $_GET variables for email verification
if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {

    // Prepare a select statement
    $sql = "SELECT email, user_level, hash FROM users WHERE email = ? AND hash = ? AND user_level = 0";

    //Prepare statement to check if email and hash are correct
    if ($stmt = mysqli_prepare($link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_hash);
            
        // Set parameters
        $param_email = trim($_GET["email"]);
        $param_hash = trim($_GET["hash"]);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {

            /* store result */
            mysqli_stmt_store_result($stmt);
            
            //Check email and hash matches a user with 0 user level
            if (mysqli_stmt_num_rows($stmt) == 1) {
                
                //Set email and hash variables
                $email = trim($_GET["email"]);
                $hash = trim($_GET["hash"]);

                //Set errors to empty
                $email_err = "";
                $hash_error = "";
            } else {
                //Couldn't find a matching email and hash with user level 0
                echo '<div class="alert alert-danger m-0"> Sorry we couldn\'t match your details to our database or this account is already verified </div>';
            }
        } else {
            echo '<div class="alert alert-danger m-0"> Oops! Something went wrong. Please try again later. </div>';
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Check for errors before inserting in database
    if (empty($email_err) && empty($hash_error)) {
        
        // Prepare an update statement
        $sql = "UPDATE users SET user_level = '1' WHERE email = ?";
         
        if ($stmt = mysqli_prepare($link, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                //Update user level for user session
                $_SESSION["user_level"] = 1; 
                    
                // Redirect to home page
                header("location: index.php");
            } else {
                echo '<div class="alert alert-danger m-0"> Oops! Something went wrong. Please try again later. </div>';
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
} else {
    // Sorry the url you have entered is invalid
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