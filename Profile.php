<?php
session_start();
require "connection.php";
include("header.php");



// Set up connection; redirect to log in if cannot connect or not logged in
if (filter_input(INPUT_COOKIE, "auth") != 1) {
    header("Location: userlogin.php");
    exit;
}

//variables
$username = $_SESSION["username"];

//get connection
$conn = getConnection();
if ($conn == NULL) {
    header("Location: no_connection.php");
    exit;
}

$sql = "SELECT f_name, l_name, email, balance, gamesplayed "
        . "FROM saltysailors "
        . "INNER JOIN saltybank ON saltysailors.username = saltybank.username "
        . "WHERE saltysailors.username = '$username'";
$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
if (mysqli_num_rows($res) < 1) {
    header("Location: userlogin.html"); // This user is not recognized so kick back to landing page.
    exit;
} else {
    while ($row = mysqli_fetch_array($res)) {
        $fname = stripslashes($row['f_name']);
        $lname = stripslashes($row['l_name']);
        $email = stripslashes($row['email']);
        $balance = stripslashes($row['balance']);
        $numplayed = stripslashes($row['gamesplayed']);
    }
    $res->free();
}

if ($balance < 25) {
    $sql4 = "UPDATE saltybank SET balance='500' WHERE username='$username'";
    mysqli_query($conn, $sql4) or die(mysqli_error($con));
    $update = "<strong>Info!</strong> You've been reset to 500 Salty Coins.";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">



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
            table{
                border:1px solid lightgray;
                padding: 10px;
                background-color: #f1f1f1;
            }

        </style>
    </head>

    <center>        
        <body>
            <div align="center">
                <div style="color: darkcyan"><?php echo $update; ?></div>
                <form>
                    <h1>Welcome to the Salty Lounge, <?php echo $fname; ?>!</h1>
                    <fieldset><legend><h3>This is you...right?</h3></legend>

                        <table cellspacing='10' align='center'>
                            <tr><td><b>Username:</b></td><td><?php echo $username; ?></td></tr>
                            <tr><td><b>First Name:</b></td><td><?php echo $fname; ?></td></tr>
                            <tr><td><b>Last Name:</b></td><td><?php echo $lname; ?></td></tr>
                            <tr><td><b>Email:</b></td><td><?php echo $email; ?></td></tr>

                        </table>
                    </fieldset>
                    <br/>
                    <div>
                        <fieldset><legend><h3>Your Salty Coins:</h3></legend>
                            <?php echo  $balance; ?>
                        </fieldset>
                        <br/>
                        <fieldset><legend><h3>Total games played:</h3></legend>
                            <?php echo $numplayed; ?>
                        </fieldset>
                    </div>
                </form>
            </div>
        </body>
    </center>
</html>