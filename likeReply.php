<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

$userId = $replyId = $postId = "";;
$unlike = false;

//Check request method is GET and postID + userID is set
if ($_SERVER["REQUEST_METHOD"] == "GET" AND isset($_GET['replyId']) AND !empty($_GET['replyId'])
    AND isset($_GET['userId']) AND !empty($_GET['userId']) AND isset($_GET['postId']) AND !empty($_GET['postId'])) {

    //Check if user has already liked post
    // Prepare a select statement
    $sql = "SELECT like_id FROM likes WHERE like_user_id = ? AND like_reply_id = ?";
        
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $param_userID, $param_replyID);
        
        // Set parameters
        $param_userID = trim($_GET['userId']);
        $param_replyID = trim($_GET['replyId']);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) == 1) { //Post is already liked
                //continue to unlike reply
                $unlike = true;
            } else {
                //Set variables to continue with request
                $userId = trim($_GET["userId"]);
                $replyId = trim($_GET["replyId"]);
                $postId = trim($_GET["postId"]);
            }
        } else {
            header('HTTP/1.0 500 Internal Server Error');
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    //Check everything is validated to like the reply
    if (!empty($replyId) AND !empty($userId) AND !empty($postId)) {

        // Prepare an insert statement
        $sql = "INSERT INTO likes (like_user_id, like_reply_id) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_userID, $param_replyID);
    
            // Set parameters
            $param_userID = $userId;
            $param_replyID = $replyId;
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Success
            } else { //Send back ajax error
                header('HTTP/1.0 500 Internal Server Error');
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Increment database reply likes
        $sql = "UPDATE replies SET reply_likes = reply_likes + 1 WHERE reply_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_replyID);
    
            // Set parameters
            $param_replyID = $replyId;
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Success
            } else { //Send back ajax error
                header('HTTP/1.0 500 Internal Server Error');
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Add to total likes in the post
        $sql = "UPDATE posts SET likes = likes + 1 WHERE post_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_postID);
    
            // Set parameters
            $param_postID = trim($_GET['postId']);
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Success
            } else { //Send back ajax error
                header('HTTP/1.0 500 Internal Server Error');
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    //Unlike post
    } else if ($unlike == true) {
        // Prepare a delete statement
        $sql = "DELETE FROM likes WHERE like_user_id = ? AND like_reply_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_userID, $param_replyID);
    
            // Set parameters
            $param_userID = trim($_GET['userId']);
            $param_replyID = trim($_GET['replyId']);
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Success
            } else { //Send back ajax error
                header('HTTP/1.0 500 Internal Server Error');
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Decrement database post likes
        $sql = "UPDATE replies SET reply_likes = reply_likes - 1 WHERE reply_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_replyID);
    
            // Set parameters
            $param_replyID = trim($_GET['replyId']);
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Success
            } else { //Send back ajax error
                header('HTTP/1.0 500 Internal Server Error');
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Decrement from total post likes
        $sql = "UPDATE posts SET likes = likes - 1 WHERE post_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_postID);
    
            // Set parameters
            $param_postID = trim($_GET['postId']);
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Success
            } else { //Send back ajax error
                header('HTTP/1.0 500 Internal Server Error');
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

} else { //Send back ajax error
    header('HTTP/1.1 403 Forbidden');
}
?>