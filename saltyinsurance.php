<?php

$username = $_SESSION["username"];
$sql3 = "SELECT balance FROM saltybank WHERE username= '$username'";
$res = mysqli_query($conn, $sql3) or die(mysqli_error($conn));
if (mysqli_num_rows($res) < 1) {
    header("Location: userlogin.php"); // This user is not recognized so kick back to landing page.
    exit;
} else {
    while ($row = mysqli_fetch_array($res)) {
        $balance = stripslashes($row['balance']);
    }
    $res->free();
}

if($balance < 0){
    $sql4 = "UPDATE saltybank SET balance = '500' WHERE username='$username'";
    mysqli_query($con, $sql4) or die(mysqli_error($con));
}
?>
