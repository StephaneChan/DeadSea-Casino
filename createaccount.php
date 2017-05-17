<?php
//connect to server and select database
session_start();
require "connection.php";


$mysqli = mysqli_connect("cosc304.ok.ubc.ca", "btruong", "btruong", "db_btruong");

$username = $_POST["username"];
$password = $_POST["password"];
$confirmpassword = $_POST["confirmpassword"];
$firstname = $_POST["f_name"];
$lastname = $_POST["l_name"];
$email = $_POST["email"];

// if page is not submitted to itself echo the form
if (!isset($_POST['submit']) || ($_POST["password"] != $_POST["confirmpassword"])) {
    if ($_POST["password"] != $_POST["confirmpassword"]) {
        echo "Passwords do not match!";
    }
    ?>
    <html>
        <head>
            <title>Creating Account</title>
            <link rel="stylesheet" type="text/css" href="SaltyCss.css">
            <style type="text/css">
                input{
                    border:1px solid lightgray;
                    padding: 10px 20px;
                    border-radius:5px;
                    font-size: 16px;
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
                body{
                    padding-top: 5%; 
                    font-size: 16px;
                }
                p{
                    font-style: italic;
                    color: darkslategrey;
                    font-size: 18px;
                }
            

            </style>
        </head>

        <body>
        <center>
            <!form method="post" action="<?php echo $PHP_SELF; ?>">
            <img src="http://i.imgur.com/aILONEe.png">
            <p>In salt, we trust!</p>
            <form method="post" action="createaccount.php">
                
                <fieldset><legend><h1>Create your account</h1></legend>
                    <table cellspacing='5' align='center'>
                        <tr><td>User name:</td><td><input type='text' name='username' required/></td></tr>
                        <tr><td>Password:</td><td><input type='password' name='password' required/></td></tr>
                        <tr><td>Re-enter Password:</td><td><input type='password' name='confirmpassword' required/></td></tr>
                        <tr><td>First Name:</td><td><input type='text' name='f_name' required/></td></tr>
                        <tr><td>Last name:</td><td><input type='text' name='l_name' required/></td></tr>
                        <tr><td>Email:</td><td><input type='text' name='email' required/></td></tr>

                        <tr><td></td><td><input type='submit' name='submit' value='Create account'/></td></tr>
                    </table>
                    </fielset>
                    <div align="left">
                        <a href="userlogin.html">Return to login</a>
                    </div> 
            </form>
        </center>
    </body>

    </html>
    <?php
} else {
//create and issue the query
    $query = mysqli_query($mysqli, "SELECT * FROM saltysailors WHERE username='" . $username . "'");
    if (mysqli_num_rows($query) > 0) {
        echo "<html><body><center><form><h3> Username is in use already.<br> <a href='createaccount.php'>Try Again!</a></h3></form></center></body></html>";
    } else {
        $sql = "INSERT INTO saltysailors (username, password, f_name, l_name, email)
        VALUES ('$username', PASSWORD('$password'), '$firstname', '$lastname', '$email')";

        $sql2 = "INSERT INTO saltybank VALUES ('$username', 500, 0)";

        if (($mysqli->query($sql) === TRUE) && ($mysqli->query($sql2) === TRUE)) {
            echo "<html><body><center><form><h3>Your account, <b>" . $username . "</b> has been created. Thank you for joining us!<br /><p><a href=\"userlogin.html\">Sign In</a></p></h3></form></center></body></html>";
        } else {
            echo "Error: " . $mysqli . "<br>" . $mysqli->error;
        }
    }
}
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>


