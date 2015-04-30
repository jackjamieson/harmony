<?php

if(isset($_GET)){

    $roomId = $_GET['id'];
    //readfile('/var/www/html/liq/' . $roomId . '-playlist-full.pls') or die ("Unable to read playlist.");
    
    $handle = fopen('/var/www/html/liq/' . $roomId . '-playlist-full.pls', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            echo $line;
        }

        fclose($handle);
    } else {
        // error opening the file.
    } 
}



?>
