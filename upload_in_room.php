<?php
//TEST IF SESSION IS OPEN
echo 'BAHAKSHFKUD'.$_SESSION['User_id'];
session_start();

//Set up the music manager
include "MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$databaseConnected = $manager->connectToDatabase();

ini_set( 'display_errors', 'On');// Turn on debugging.

require_once('getid3/getid3/getid3.php');
include ('template/aws.php');// Include our aws services
include ('template/util.php');// Utility class for generating liquidsoap files

$aws = new AWS();
$s3Client = $aws->authS3();// Authorize the S3 object.

$bucket = $aws->getBucket();// Get the bucket name for our music uploads

//check whether a form was submitted
if(isset($_POST['Submit'])){

	// id3 information
	// Initialize getID3 engine
	$getID3 = new getID3;

	$roomId = $_GET['id'];

	//$id = uniqid();// generate unique id for the room

	//retreive post variables
	$fileName = $_FILES['theFile']['name'];
	$fileTempName = $_FILES['theFile']['tmp_name'];
	$awsFileName = time();

  
	// Analyze file and store returned data in $ThisFileInfo
	$songid3info = $getID3->analyze($fileTempName);
	getid3_lib::CopyTagsToComments($songid3info);
					
	$songArtist = "";
	$songTitle = "";
					
	// extract the artist and title info
	if(isset($songid3info['comments_html']['artist'][0]))
		$songArtist = $songid3info['comments_html']['artist'][0]; // artist from any/all available tag formats
	if(isset($songid3info['tags']['id3v2']['title'][0]))
		$songTitle = $songid3info['tags']['id3v2']['title'][0];  // title from ID3v2
			
	if(strlen($songArtist) < 1 || strlen($songTitle) < 1){
					
	// set the titles to unknown so they aren't null in the database
		$timestamp = time();
		$songArtist = "Unknown Artist " . $timestamp;
		$songTitle = "Unknown Title " . $timestamp;
		}
  
    if(preg_match("/\.(mp3)$/", $fileName)){
        
      if($_FILES['theFile']['error'] > 0){
        echo "return code: " . $_FILES['theFile']['error'];

        if($_FILES['theFile']['error'] == 4){
          echo ' No file was uploaded.  Is it a music file?';
        }
      }

      $result = $aws->uploadSong($s3Client, $fileTempName, $awsFileName);// Upload the file to AWS
	  $locationString = "https://user-music-folder.s3.amazonaws.com/Music/" . $awsFileName . ".mp3";
	  
	  $debug = $manager->addSong($songTitle, $songArtist, null, null, $locationString);
	  //echo "Debug error: " . $debug;
	
      $util = new Util();

      // Update the pls file on the web server
      // This contains the mp3 urls on S3.
      $util->updatePlaylistFile($result, $roomId);

      // update the full file to read the playlist
      $util->updatePlaylistFileFull($result, $roomId, $_SESSION['User_id']);
        ?>
        <script>
         parent.upload_completed();
        </script>
        <?php

    }
    else{
        ?>
        <script>
         parent.upload_failed();
        </script>
<?php
        
    }



}

?>

