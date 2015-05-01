<?php
session_start();
include "/var/www/html/MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$databaseConnected = $manager->connectToDatabase();


if(isset($_GET)){



    $roomId = $_GET['id'];
    //readfile('/var/www/html/liq/' . $roomId . '-playlist-full.pls') or die ("Unable to read playlist.");
    
    $handle = fopen('/var/www/html/liq/' . $roomId . '-playlist-full.pls', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $lines = explode(";", $line);
	        $songInfo = $manager->getUserSongFromLocation($lines[0], $lines[1]);
	    
            echo "<b>" . $songInfo['artist'] . "</b> - " . $songInfo['title'] . "<br>Rating: " . $songInfo['rating'] . "<p></p>";
        }

        fclose($handle);
    } else {
        // error opening the file.
    } 
}



?>
