<?php

if(isset($_GET)){

session_start();
include "MusicManger.php";
$manager = new MusicManager();
$databaseConnected = $manager->connectToDatabase($_SESSION['User_id']);

    $roomId = $_GET['id'];
    //readfile('/var/www/html/liq/' . $roomId . '-playlist-full.pls') or die ("Unable to read playlist.");
    
    $handle = fopen('/var/www/html/liq/' . $roomId . '-playlist-full.pls', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $lines = explode(";", $line);
	    $songInfo = $manager->getUserSongFromLocation($lines[0], $lines[1]);
	    
            echo $songInfo['artist'] . " - " . $songInfo['title'] . " Rating: " . $songInfo['rating'];
        }

        fclose($handle);
    } else {
        // error opening the file.
    } 
}



?>
