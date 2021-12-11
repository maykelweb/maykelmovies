<?php
// Initialize the session
session_start();
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $email = "";
$username_err = $email_err = $reset_err = $err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else if (!preg_match("/^[^@]+@[^@]+\.[a-z]{2,6}$/i", trim($_POST["email"]))) {
        $email_err = "Email is not valid.";
        
    // Validate username
    } else if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {

        // Prepare a select statement
        $sql = "SELECT email, username FROM users WHERE email = ? AND username = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_username);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                //If rows == 1 it means a user exists with that email and username
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    //Continue with code below
                } else {
                    $reset_err = "Sorry but there are no accounts associated with that username or email";
                }
            } else {
                echo '<div class="alert alert-danger m-0"> Oops! Something went wrong. Please try again later. </div>';
            }
        }
        
        mysqli_stmt_close($stmt);
    }

    //Check for errors before proceeding
    if (empty($username_err) && empty($reset_err) && empty($email_err) && empty($err)) {

        //Create password reset token to store in database
        $token = md5( rand(0,1000) ); // Generate random 32 character hash and assign it to a local variable.
        // Example output: f4552671f8909587cf485ea990207f3b

        //Set expiry date on hash
        $expDate = strtotime('+20 minutes');
                    
        // Prepare an insert statement
        $sql = "INSERT INTO tokens (token_email, token) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_token);
        
            // Set parameters
            $param_email = trim($_POST["email"]);
            $param_token = $token;
        
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Send email with password reset link
                try {
                    $to      = trim($_POST["email"]); // Send email to our user
                    $subject = 'Forgotten Username'; // Give the email a subject 
                    $message = '
            
                    A request was made for a forgetten password. If this request was not made by you please make sure your accounts are secured.
                            
                    To reset your password please click the link below:
                    https://www.maykelmovies.xyz/reset-password.php?email='.$email.'token='.$hash.'
            
                    '; // Our message above including the username
                    
                    //Create success message to display at login.php
                    $_SESSION['loginMsg'] = "An email has been sent with a username reminder";
                    // Redirect user to login page
                    header("location: login.php");

                } catch (Exception $e) {
                    echo '<div class="alert alert-danger m-0"> Oops! Something went wrong. Please try again later. </div>';
                    $err = "error: " . $e;
                }
            } else{
                echo '<div class="alert alert-danger"> Oops! Something went wrong. Please try again later. </div>';
                $err = "true";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="background">
        <div class="wrapper">
            <h2>Forgot Password</h2>
            <p>Enter your username and email address to be sent a link to reset your password.</p>
            
            <?php
            if (!empty($reset_err)) {
                echo '<div class="alert alert-danger">' . $reset_err . '</div>';
            }   
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div> 
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Send Email">
                </div>
            </form>
        </div>   
    </div> 
</body>
</html>