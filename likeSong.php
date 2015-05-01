<?php
//This script handles liking, disliking, and grabbing songs in the playlist.

//Open session to access user variables.
session_start();

//Start MusicManager to make database queries.
include "MusicManger.php";
$manager = new MusicManager();
$connected = $manager->connectToDatabase();

//Like button pressed
if(isset($_POST['Up']))
{
	$result = $manager->likeSong($_POST['ID'], $_SESSION['User_id']);
}

//Dislike button pressed
else if(isset($_POST['Down']))
{
	$result = $manager->dislikeSong($_POST['ID'], $_SESSION['User_id']);
}

//Grab button pressed
else if (isset($_POST['Grab']))
{
	$result = $manager->grabSong($_SESSION['User_id'], $_POST['ID'], $_POST['Song'], $_POST['Artist']);
}

?>
