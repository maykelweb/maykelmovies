<?php
// Initialize the session
session_start();
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = "";
$email_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else if (!preg_match("/^[^@]+@[^@]+\.[a-z]{2,6}$/i", trim($_POST["email"]))) {
        $email_err = "Email is not valid.";
    } else {
        // Prepare a select statement
        $sql = "SELECT user_id, username FROM users WHERE email = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                //Check we get back 1 result matching email
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username);
                    if (mysqli_stmt_fetch($stmt)) {
                        //Send email with username
                        try {
                            $to      = trim($_POST["email"]); // Send email to our user
                            $subject = 'Forgotten Username'; // Give the email a subject 
                            $message = '
                
                            A request was made for a forgetten username. If this request was not made by you please make sure your accounts are secure.
                            </br></br>
                            Your username is: '. $username. '
                
                        '; // Our message above including the username
                          
                        $headers = 'From:confirmation@maykelmovies.xyz' . "\r\n"; // Set from headers
                        mail($to, $subject, $message, $headers); // Send our email
                
                        //Create success message to display at login.php
                        $_SESSION['loginMsg'] = "An email has been sent with a username reminder";
                        // Redirect user to login page
                        header("location: login.php");
                        
                        } catch (Exception $e) {
                            echo 'Caught exception: ',  $e->getMessage(), "\n";
                        }
                    }
                } else {
                    $email_err = "Sorry we don't have this email address in our servers.";
                }
            } else {
                echo '<div class="alert alert-danger m-0"> Oops! Something went wrong. Please try again later. </div>';
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
    <title>Forgot Username</title>
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
        <h2>Forgot Username</h2>
        <p>Enter the email you used to register to recieve an email with your username.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address..." class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>  
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send Email">
            </div>
        </form>
    </div>   
</div> 
</body>
</html>