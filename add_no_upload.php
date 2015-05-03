<?php

session_start();

//Set up the music manager
include "MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$databaseConnected = $manager->connectToDatabase();

ini_set( 'display_errors', 'On');// Turn on debugging.

//require_once('getid3/getid3/getid3.php');
//include ('template/aws.php');// Include our aws services
include ('template/util.php');// Utility class for generating liquidsoap files

//$aws = new AWS();
//$s3Client = $aws->authS3();// Authorize the S3 object.

//$bucket = $aws->getBucket();// Get the bucket name for our music uploads

//check whether a form was submitted
if(isset($_POST['Submit'])){

	// id3 information
	// Initialize getID3 engine
	//$getID3 = new getID3;
    
	$roomId = $_GET['id'];
    $songUrl = $_POST['song'];

	  //echo "Debug error: " . $debug;
	
      $util = new Util();

      // Update the pls file on the web server
      // This contains the mp3 urls on S3.
      $util->updatePlaylistFileNoUpload($songUrl, $roomId);

      // update the full file to read the playlist
      $util->updatePlaylistFileFullNoUpload($songUrl, $roomId, $_SESSION['User_id']);
    
    echo '<pre>' .  $songUrl . ' - ' . $roomId . '</pre>';
        ?>
        <script>
         parent.upload_completed();
        </script>
        <?php

    }
    else{
        ?>
        <script>
         //parent.upload_failed();
        </script>
<?php
        
    }
?>