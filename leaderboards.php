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


$sql = "SELECT * FROM saltybank ORDER BY balance DESC";
$sql2 = "SELECT * FROM saltybank WHERE username = '$username'";
$res = mysqli_query($mysqli, $sql2) or die(mysqli_error($mysqli));
if (mysqli_num_rows($res) < 1) {
    header("Location: userlogin.html"); // This user is not recognized so kick back to landing page.
    exit;
} else {
    while ($row = mysqli_fetch_array($res)) {
        $balance = stripslashes($row['balance']);
    }
    $res->free();
}
$display_block = "";

//    echo $sql;
//FINDING RESULTS
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

$display_block .= "<table > <tr> "
        . "<th>Ranking</th>"
        . "<th>Username</th>"
        . "<th>Balance</th>"
        . "<th>Games Played</th> </tr>";

//adding contents to table
if ($result->num_rows > 0) {
    $i = 1;
    //keep fetching results until none or 20 results
    while ($row = $result->fetch_assoc()) {
        $display_block .= "<tr>";
        $display_block .= "<td>" . $i . "</td>";
        $display_block .= "<td>" . $row['username'] . "</td>";
        $display_block .= "<td>" . $row['balance'] . "</td>";
        $display_block .= "<td>" . $row['gamesplayed'] . "</td>";
        $i++;
    }
} else {
    echo "0 results";
}
if ($balance < 25) {
    $sql4 = "UPDATE saltybank SET balance='500' WHERE username='$username'";
    mysqli_query($conn, $sql4) or die(mysqli_error($con));
    $update = "<strong>Info!</strong> You've been reset to 500 Salty Coins.";
}

$display_block .= "</table>";
?>
<html>
    <head>
        <style>
            table, th, td {
                border: 1px solid black;
                padding: 10px;
                text-align: center;
                border-collapse: collapse;

            }
            table{
                width:95%;
            }
            th{
                background-color: steelblue;
                color:white;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            tr:nth-child(odd) {
                background-color: gainsboro;
            }
            h1{
                color: #145252;
                font-size:24px;
                text-align:center;
            }
            form{
                background-color: white;
                width: 60%;
                padding: 20px;
                border-style: solid;
                border-width: 1px;
                border-radius: 4px;
                border-color: grey;
            }


        </style>
        <title>LeaderBoards</title>
    </head>
    <body>
        <div align="center" style="color: darkcyan; font-size:large;">
            <div style="color: darkcyan"><?php echo $update; ?></div>
            <img src="http://www.knowabouthealth.com/wp-content/uploads/2009/08/saltshaker.gif" height="20" width="20"/><b><?php echo " Salty Coins: " . $balance; ?></b>
        </div>
    <center>
        <form>
            <fieldset>
                <legend><h1>Dead Sea Leaderboards</h1></legend>
                <?php
                echo $display_block;
                ?>
            </fieldset>
        </form>
    </center>
</body>
</html>