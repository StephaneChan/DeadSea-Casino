<!DOCTYPE html>
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
}
//rock = 0, paper = 1, scissors = 2
// rock < paper < Scissors < rock\
//calculating result
function Result($user, $cpu, $betamt) {
    if ($user == $cpu) { //if same
        $result_text = tie($betamt);
    } elseif ($user == 0 and $cpu == 2) { //rock vs scissor
        $result_text = win($betamt);
    } elseif ($user == 2 and $cpu == 0) { // scissor vs rock
        $result_text = lose($betamt);
    } elseif ($user > $cpu) { //everything else
        $result_text = win($betamt);
    } else {
        $result_text = lose($betamt);
    }
    return $result_text;
}

//win/lose strings + QUERY updates
function win($betamt) {
//    echo "You won! You Have doubled your bet!";

    $username = $_SESSION["username"];


    $con = getConnection();
    $sql = "UPDATE saltybank SET balance = balance+" . $betamt . " WHERE username = '$username'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = '$username'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));

    return "You won! You have doubled your bet and won " . $betamt * 2 . " Salty Coins </br>"
            . "<img src=\"http://rs198.pbsrc.com/albums/aa156/Nintendodude_2007/Kirby/kirby_svictorydance.gif~c200\""
            . "style=\"width:128px;height:128px;\"/> ";
}

function lose($betamt) {
//    echo "You lose! You have lost your bet!";


    $username = $_SESSION["username"];


    $con = getConnection();
    $sql = "UPDATE saltybank SET balance = balance-" . $betamt . " WHERE username ='$username'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = '$username'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));

    return "You lose! You have lost your bet!</br>"
            . "<img src=\"http://i.imgur.com/YX6ZTJ2.gif?noredirect\""
            . "style=\"width:128px;height:128px;\"/> ";
}

function tie($betamt) {
//    echo "It was a tie! No money was lost! Try Again!";


    $username = $_SESSION["username"];


    $con = getConnection();
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = '$username'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));

    return "It was a tie! No money was lost! Try Again!</br>"
            . "<img src=\"https://45.media.tumblr.com/0b845d5c8f60ced8fa5fc7af3db4d5b2/tumblr_n8pqhqmpdh1th4xn0o1_400.gif\""
            . "style=\"width:128px;height:128px;\"/> ";
}

//creates computer choice
function CompRPS() {
    $cpu = rand(0, 2);
    return $cpu;
}

//changing value to int for function
//when player decides to play game
if (isset($_POST['play'])) {

    $selection = filter_input(INPUT_POST, 'rps');
    $betamt = filter_input(INPUT_POST, 'betamt');
//    echo $betamt;
    if ($betamt <= $balance) {
        if ($selection == "Rock") {
            $user = 0;
        } elseif ($selection == "Paper") {
            $user = 1;
        } else {
            $user = 2;
        }

        $cpu = CompRPS();

        if ($cpu == 0) {
            $cpuSelection = "Rock";
        } elseif ($cpu == 1) {
            $cpuSelection = "Paper";
        } else {
            $cpuSelection = "Scissors";
        }

        $result .= Result($user, $cpu, $betamt);
        $message = "You have chosen <strong>" . $selection . "</strong> and the CPU has chosen <strong>" .
                $cpuSelection . "</strong><h3>" . $result . "</h3>";
    } else {
        $message = "Insufficient Salty Coins.";
    }
}
?>

<html>
    <head>
        <title>RockPaperScissors</title>
        <style>
            legend {
                text-align: center;
            }
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
        </style>
    </head>
    <body>
        <div align="center" style="color: darkcyan; font-size:large;">
            <img src="http://www.knowabouthealth.com/wp-content/uploads/2009/08/saltshaker.gif" height="20" width="20"/><b><?php echo " Salty Coins: " . $balance; ?></b>
        </div>
    <center>
        <form method="post" action=RPS.php>   
            <fieldset> <legend><h1>Rock Paper Scissors</h1></legend>
                <center><h5>Pick rock, paper, or scissor!</h5></center>
                <table cellspacing='5' align='center'>

                    <tr>
                        <td>Enter amount to bet:</td>
                        <td><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="betamt" required/></td>
                    </tr>
                    <tr>
                        <td>Select your choice:</td>
                        <td>
                            <input type="radio" name="rps" value="Rock" required> Rock
                            <input type="radio" name="rps" value="Paper" required> Paper
                            <input type="radio" name="rps" value="Scissors" required> Scissors
                        </td>
                    </tr>    
                    <tr>
                        <td></td>
                        <td><input type="submit" name="play" value= "Play Game"/></td>
                    </tr>
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
