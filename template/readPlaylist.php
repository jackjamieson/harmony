<?php
session_start();
include "/var/www/html/MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$databaseConnected = $manager->connectToDatabase();


if(isset($_GET)){


            error_reporting(E_ALL);


            ini_set( 'display_errors', 'On');// Turn on debugging.


    $roomId = $_GET['id'];
    //readfile('/var/www/html/liq/' . $roomId . '-playlist-full.pls') or die ("Unable to read playlist.");
    
    $handle = fopen('/var/www/html/liq/' . $roomId . '-playlist-full.pls', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $lines = explode(";", $line);
	        $songInfo = $manager->getUserSongFromLocation($lines[0], $lines[1]);
            $liked = $manager->checkIfLiked($songInfo['song_id'], $lines[1]);
            

            // like 0 if fails, 1 if user has not like, 2 if has liked
	       if($liked == 2)
            {
               echo "<b>" . $songInfo['artist'] . "</b> - " . $songInfo['title'] . "<br>Rating: " . $songInfo['rating'] . "<br><form action='likeSong.php' method='post' target='" . $songInfo['song_id'] . "-hidden'><div class='btn-group btn-group-sm' role='group'><button type='submit' name='Up' disabled='disabled' id='" . $songInfo['song_id'] . "-u' class='btn btn-primary'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> Upvote</a><button type='submit' disabled='disabled' name='Down' id='" . $songInfo['song_id'] . "-d' class='btn btn-primary'><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span> Downvote</a><button type='submit' name='Grab' id='" . $songInfo['song_id'] . "-g' class='btn btn-primary'><span class='glyphicon glyphicon-heart-empty' aria-hidden='true'></span> Grab</a>
</div><input type='text' name='Artist' class='form-control' value='" . $songInfo['artist'] . "' style='display:none'><input type='text' name='Song' class='form-control' value='" . $songInfo['title'] . "' style='display:none'><input type='text' name='ID' class='form-control' value='" . $songInfo['song_id'] . "' style='display:none'><input type='text' name='u_id' class='form-control' value='" . $lines[1] . "' style='display:none'></form><iframe id='" . $songInfo['song_id'] . "-hidden' name='" . $songInfo['song_id'] . "-hidden' style='display:none' ></iframe><p></p>";
            }
            else {
                echo "<b>" . $songInfo['artist'] . "</b> - " . $songInfo['title'] . "<br>Rating: " . $songInfo['rating'] . "<br><form action='likeSong.php' id='f" . $songInfo['song_id'] . "-form' method='post' target='" . $songInfo['song_id'] . "-hidden'><div class='btn-group btn-group-sm' role='group'><button type='submit' name='Up' id='" . $songInfo['song_id'] . "-u' class='btn btn-primary'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> Upvote</a><button type='submit' name='Down' id='" . $songInfo['song_id'] . "-d' class='btn btn-primary'><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span> Downvote</a><button type='submit' name='Grab' id='" . $songInfo['song_id'] . "-g' class='btn btn-primary'><span class='glyphicon glyphicon-heart-empty' aria-hidden='true'></span> Grab</a>
    </div><input type='text' name='Artist' class='form-control' value='" . $songInfo['artist'] . "' style='display:none'><input type='text' name='Song' class='form-control' value='" . $songInfo['title'] . "' style='display:none'><input type='text' name='ID' class='form-control' value='" . $songInfo['song_id'] . "' style='display:none'><input type='text' name='u_id' class='form-control' value='" . $lines[1] . "' style='display:none'></form><iframe id='" . $songInfo['song_id'] . "-hidden' name='" . $songInfo['song_id'] . "-hidden' style='display:none' ></iframe><p></p>";
            }
        }
        
        

        fclose($handle);
    } else {
        // error opening the file.
    } 
}



?>
