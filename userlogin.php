<?php
session_start();
require 'connection.php';
include ("header.php");
//check for required fields from the form
if ((!filter_input(INPUT_POST, 'username')) || (!filter_input(INPUT_POST, 'password'))) {
//if ((!isset($_POST["username"])) || (!isset($_POST["password"]))) {

    header("Location: userlogin.html");
    exit;
}

//connect to server and select database
$mysqli = getConnection();
//For more info about mysqli functions, go to the site below:
//http://www.w3schools.com/php/php_ref_mysqli.asp

/* create and issue the query
  $sql = "SELECT firstname, lastname FROM members WHERE username = '".$_POST["username"].
  "' AND password = PASSWORD('".$_POST["password"]."')";
 */

//create and issue the query
$targetname = filter_input(INPUT_POST, 'username');
$targetpasswd = filter_input(INPUT_POST, 'password');
$sql = "SELECT username FROM saltysailors WHERE username = '" . $targetname .
        "' AND password = PASSWORD('" . $targetpasswd . "')";

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//get the number of rows in the result set; should be 1 if a match
if (mysqli_num_rows($result) == 1) {

    //if authorized, get the values of f_name l_name
    while ($info = mysqli_fetch_array($result)) {
        $username = stripslashes($info['username']);
    }

    //set session items for user to be used throughout website
    $_SESSION["username"] = $username;

    //set authorization cookie
    setcookie("auth", "1", time() + 60 * 30 * 24, "/", "", 0);

    //create display string
    $display_block = "
        <body style='background-color:azure'>
	<p>Welcome to the SaltyLounge, <b>" . $targetname . "</b>!</p>
        
	
        </body>";
} else {
    //redirect back to login form if not authorized

    header("Location: userlogin.html");

    exit;
}
?>
<html>
    <link rel="stylesheet" type="text/css" href="SaltyCss.css">
    <head>
        <title>Home</title>

        <?php
        header("Location: Profile.php");
        exit;
        ?>


    </head>
    <body>

    </body>
</html>