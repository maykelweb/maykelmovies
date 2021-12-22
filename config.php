<?php
/* Database credentials*/

/* Siteground Access
define('DB_SERVER', '35.214.100.93');
define('DB_USERNAME', 'usd9ijvls5glr');
define('DB_PASSWORD', 'Z0oUpJ9:Xi');
define('DB_NAME', 'dbgy0mvpoedqdw');
*/

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Z0oUpJ9:Xi');
define('DB_NAME', 'MovieSite');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>