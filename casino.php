<?php
session_start();
require "connection.php";
include("header.php");
$username = $_SESSION["username"];
// Set up connection; redirect to log in if cannot connect or not logged in
if (filter_input(INPUT_COOKIE, "auth") != 1) {
    header("Location: userlogin.php");
    exit;
}
$mysqli = getConnection();
if ($mysqli == NULL) {
    header("Location: no_connection.php");
    exit;
}
$sql5 = "SELECT * FROM saltybank WHERE username = '$username'";
$res = mysqli_query($mysqli, $sql5) or die(mysqli_error($mysqli));
if (mysqli_num_rows($res) < 1) {
    header("Location: userlogin.html"); // This user is not recognized so kick back to landing page.
    exit;
} else {
    while ($row = mysqli_fetch_array($res)) {
        $balance = stripslashes($row['balance']);
    }
    $res->free();
}
if ($balance < 25) {
    $sql4 = "UPDATE saltybank SET balance='500' WHERE username='$username'";
    mysqli_query($conn, $sql4) or die(mysqli_error($con));
    $update = "<strong>Info!</strong> You've been reset to 500 Salty Coins.";
}
?>
<html>

    <head>
        <title>Casino</title>
        <link rel="stylesheet" type="text/css" href="SaltyCss.css">
        <style type="text/css">
            h1{
                color: #145252;
                font-size:24px;
                text-align:center;
            }
            h3{
                color: darkcyan;
                font-size:16px;
                text-align:center;  
            }
            body{
            }
        </style>
    </head>
    <body>
        <div align="center" style="color: darkcyan; font-size:large;">
            <div style="color: darkcyan"><?php echo $update; ?></div>
            <img src="http://www.knowabouthealth.com/wp-content/uploads/2009/08/saltshaker.gif" height="20" width="20"/><b><?php echo " Salty Coins: " . $balance; ?></b>
        </div>
    <center>
        <h1>Welcome to the Casino!</h1>
        <form action="numguess.php" method="POST">
            <fieldset><center><legend><h3>Play the Salty Guess!</h3></legend></center>
                <table cellspacing='5' align='center'>
                    <tr><td>Guess a random number from 1-10</td><td><input type="submit" value="Play!"/></td></tr>
                </table>  
            </fieldset>
        </form>
        <form action="RPS.php" method="POST">
            <fieldset><center><legend><h3>Play Rock, Paper, Scissors!</h3></legend></center>
                <table cellspacing='5' align='center'>    
                    <tr><td>Play a game of Rock, Paper, Scissors</td><td><input type="submit" value="Play!"/></td></tr>
                </table>
            </fieldset>
        </form>
        <form action="coinflip.php" method="POST">
            <fieldset><center><legend><h3>Play Coin Flip!</h3></legend></center>
                <table cellspacing='5' align='center'>    
                    <tr><td>Play a game of coin flip</td><td><input type="submit" value="Play!"/></td></tr>
                </table>
            </fieldset>
        </form>
    </center>
</body>
</html>

