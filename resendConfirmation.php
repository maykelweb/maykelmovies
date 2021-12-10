<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

//Check if user is logged in and user level is 0 before proceeding
if (empty($_SESSION['user_level']) && $_SESSION['user_level'] !== 0) {
    // Redirect user to home page
    header("location: index.php");
}

//Check user level is 0 and requires confirmation
if ($_SESSION['user_level'] == "0") {

    try {
        //Send confirmation email
        $to      = $_SESSION['email']; // Send email to our user
        $subject = 'Verification Email'; // Give the email a subject 
        $message = '

        Thanks for signing up!
        You can create your first post after you have activated your account by pressing the url below.

        Please click this link to activate your account:
        http://www.maykelmovies.xyz/verify.php?email='.$_SESSION['email'].'&hash='.$_SESSION['hash'].'

        '; // Our message above including the link
          
        $headers = 'From:confirmation@maykelmovies.xyz' . "\r\n"; // Set from headers
        mail($to, $subject, $message, $headers); // Send our email

        //Set session user_level to 303 to show different message on homepage
        $_SESSION['user_level'] = "303";
        
        // Redirect to login page
        header("location: login.php");
        
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

//User is already verified
} else {
    // Redirect to login page
    header("location: login.php");
}
?>