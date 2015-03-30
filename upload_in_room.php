<?php

ini_set( 'display_errors', 'On');// Turn on debugging.

include ('template/aws.php');// Include our aws services
include ('template/util.php');// Utility class for generating liquidsoap files

$aws = new AWS();
$s3Client = $aws->authS3();// Authorize the S3 object.

$bucket = $aws->getBucket();// Get the bucket name for our music uploads

//check whether a form was submitted
if(isset($_POST['Submit'])){

  $roomId = $_GET['id'];

  //$id = uniqid();// generate unique id for the room

  //retreive post variables
  $fileName = $_FILES['theFile']['name'];
  $fileTempName = $_FILES['theFile']['tmp_name'];
  $awsFileName = time();


  if($_FILES['theFile']['error'] > 0){
    echo "return code: " . $_FILES['theFile']['error'];

    if($_FILES['theFile']['error'] == 4){
      echo ' No file was uploaded.  Is it a music file?';
    }
  }

  $result = $aws->uploadSong($s3Client, $fileTempName, $awsFileName);// Upload the file to AWS

  $util = new Util();

  // Update the pls file on the web server
  // This contains the mp3 urls on S3.
  $util->updatePlaylistFile($result, $roomId);

  // Connect to the server and queue up the song we just uploaded
  $util->queueSong($result, $roomId);



}

?>
<script>
 parent.upload_completed();
</script>
