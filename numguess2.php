<?php

session_start();
require 'connection.php';
include ("header.php");
$winmulti = 5;

function Guess() {
    $rand = rand(0, 1);
    return $rand;
}

$betamt = filter_input(INPUT_POST, 'betamt');
$guess = filter_input(INPUT_POST, 'guess');
$random = Guess();

if ($guess != $random) {
    lose($betamt);
    echo $guess . " is the wrong number! You have lost your bet!";
    echo " Oh btw, ".$random." was the correct number. ";
} else if ( $guess == $random) { // must be equivalent
    win($betamt);
    $amtwon = $betamt*$winmulti;
    echo "Well done! You guessed the right number! You won ".$amtwon;
}

function win($betamt) {
    $con = getConnection();
    $sql = "UPDATE saltybank SET balance = balance+" . $betamt * 5 . " WHERE username = 'btruong'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = 'btruong'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));
}

function lose($betamt) {
    $result = $random." was the correct number. ";
    $con = getConnection();
    $sql = "UPDATE saltybank SET balance = balance-" . $betamt . " WHERE username ='btruong'";
    mysqli_query($con, $sql) or die(mysqli_error($con));
    $sql2 = "UPDATE saltybank SET gamesplayed = gamesplayed+1 WHERE username = 'btruong'";
    mysqli_query($con, $sql2) or die(mysqli_error($con));
}

?>