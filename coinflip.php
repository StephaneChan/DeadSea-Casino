<?php
session_start();
require 'connection.php';
include ("header.php");

$winmulti = 3;

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

$random = rand(0, 1);
$betamt = filter_input(INPUT_POST, 'betamt');


if (isset($_POST["coin"])) {
    $coin = filter_input(INPUT_POST, 'coin');
    if ($betamt > $balance) {
        $message = "Insufficient Salty Coins";
    } else {
        if ($coin != $random) {
            if ($coin == 0) {
                $guess = tails;
            } else {
                $guess = heads;
            }
            if ($random == 0) {
                $rand = tails;
            } else {
                $rand = heads;
            }
            lose($betamt);
            $message = "You picked " . $guess . " but the coin landed on " . $rand . ".</br>"
                    . "<img src=\"http://i.imgur.com/YX6ZTJ2.gif?noredirect\""
                    . "style=\"width:128px;height:128px;\"/> ";
        } else if ($coin == $random) { // must be equivalent
            if ($coin == 0) {
                $guess = Tails;
            } else {
                $guess = Heads;
            }
            if ($random == 0) {
                $rand = Tails;
            } else {
                $rand = Heads;
            }
            win($betamt);
            $amtwon = $betamt * $winmulti;
            $message = "It landed on " . $guess . "! You won " . $amtwon . " salty coins!</br>"
                    . "<img src=\"http://rs198.pbsrc.com/albums/aa156/Nintendodude_2007/Kirby/kirby_svictorydance.gif~c200\""
                    . "style=\"width:128px;height:128px;\"/> ";
        }
    }
    $a = $_POST['update'];
    echo $a;
}

function win($betamt) {
    $username = $_SESSION["username"];
    $con = getConnection();
    $sql = "UPDATE saltybank SET balance = balance+" . $betamt * 5 . " WHERE username = '$username'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = '$username'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));
}

function lose($betamt) {
    $username = $_SESSION["username"];
    $result = $random . " was the correct number. ";
    $con = getConnection();
    $sql = "UPDATE saltybank SET balance = balance-" . $betamt . " WHERE username ='$username'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = '$username'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));
}

if ($balance < 25) {
    $sql4 = "UPDATE saltybank SET balance='500' WHERE username='$username'";
    mysqli_query($conn, $sql4) or die(mysqli_error($con));
}
?>
<html>

    <head>
        <title>Coin Toss</title>
    </head>
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
    <body>
        <div align="center" style="color: darkcyan; font-size:large;">
            <img src="http://www.knowabouthealth.com/wp-content/uploads/2009/08/saltshaker.gif" height="20" width="20"/><b><?php echo " Salty Coins: " . $balance; ?></b>
        </div>
    <center>
        <form action="" method="POST">   
            <fieldset>
                <legend><h1>Welcome to the coin toss!</h1></legend>
                <center><h5>Pick heads or tails</h5></center>
                <table cellspacing='5' align='center'>
                    <tr><td>Enter amount to bet:</td><td><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="betamt" required /></td></tr>
                    <tr><td>Pick heads or tails:</td><td>  <select name="coin">
                                <option value="1">Heads</option>
                                <option value="0">Tails</option>
                            </select></td></tr>

                    <tr><td></td><td><input type="submit" value="Play game"/></td></tr>


                </table>
                <h3><?php echo $message ?></h3>
                <div align="left">
                    <a href="casino.php">Return to the casino</a>
                </div> 
            </fieldset>

        </form>
    </center>
</body>
</html>