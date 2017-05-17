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

$display_block = "";

if (isset($_POST['submit'])) {

    $usersearch = filter_input(INPUT_POST, 'usersearch');



    $sql = "SELECT saltysailors.username, f_name, l_name, email, balance, gamesplayed "
            . "FROM saltysailors "
            . "INNER JOIN saltybank ON saltysailors.username = saltybank.username "
            . "WHERE saltysailors.username LIKE '%$usersearch%'";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));


    if ($result->num_rows > 0) {
        //keep fetching results until none or 20 results
        //table setup        
        $display_block .= "<form><fieldset>"
                . " <legend><h1>Search Results</h1></legend>"
                . "<table > <tr> "
                . "<th>Username</th>"
                . "<th>First Name</th>"
                . "<th>Last Name</th>"
                . "<th>Email</th>"
                . "<th>Balance</th>"
                . "<th>Games Played</th> </tr>";
        while ($row = $result->fetch_assoc()) {



            //table output
            $display_block .= "<tr>";
            $display_block .= "<td>" . $row['username'] . "</td>";
            $display_block .= "<td>" . $row['f_name'] . "</td>";
            $display_block .= "<td>" . $row['l_name'] . "</td>";
            $display_block .= "<td>" . $row['email'] . "</td>";
            $display_block .= "<td>" . $row['balance'] . "</td>";
            $display_block .= "<td>" . $row['gamesplayed'] . "</td>";
        }
        //table close
        $display_block .= "</table>" .
                "</fieldset>" .
                "</form>";
    } else {
        $display_block .= "<form><fieldset>"
                . " <legend><h1>Search Results</h1></legend>"
                . "There were no results!"
                ."</table>" 
                ."</fieldset>" 
                ."</form>";
    }
}
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
        <title>Search</title>
    </head>
    <body>
        <div align="center" style="color: darkcyan; font-size:large;">
            <div style="color: darkcyan"><?php echo $update; ?></div>
            <img src="http://www.knowabouthealth.com/wp-content/uploads/2009/08/saltshaker.gif" height="20" width="20"/>
            <b><?php echo " Salty Coins: " . $balance; ?></b>
        </div>
    <center>
        <form method="post" action="search.php">
            <fieldset>
                <legend><h1>Search Salty Users</h1></legend>
                <p><strong>Username: </strong>
                    <input type="text" name="usersearch" required /></p>
                <p><input type="submit" name="submit" value="Search"/>
            </fieldset>
        </form>
<?php
echo $display_block;
?>
    </center>
</body>
</html>
