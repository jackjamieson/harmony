<?php

if(isset($_GET)){

    $roomId = $_GET['id'];
    readfile('/var/www/html/liq/' . $roomId . '-playlist-full.pls') or die ("Unable to read playlist.");
}



?>
