<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Harmony</title>
        <head>

            <!-- css imports !-->
            <?php include ('template/head.php'); ?>

        </head>

        <body>

            <!-- css/html nav !-->
            <?php include ('template/nav.php') ?>


            <?php

    error_reporting(E_ALL);


ini_set( 'display_errors', 'On');// Turn on debugging.

include ('template/aws.php');// Include our aws services
include ('template/util.php');// Utility class for generating liquidsoap files

$aws = new AWS();
$s3Client = $aws->authS3();// Authorize the S3 object.

$bucket = $aws->getBucket();// Get the bucket name for our music uploads



/*THIS WILL BE MOVED LATER.
  We are leaving this upload handling script here for now
  until we move it to the proper page
*/

//check whether a form was submitted
if(isset($_POST['Submit'])){

    $id = uniqid();// generate unique id for the room

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

    // Generate a new .pls on the Linode server
    // This contains the mp3 urls on S3.
    $util->makePlaylistFile($result, $id);

    // Generate a new .liq files on the Linode Server
    // This sends data to the Icecast server to be streamed.
    $util->makeLiqFile($id);

    // Run the liquidsoap script on the server
    $util->runLiqScript($id);


}
            ?>



            <div class="jumbotron">
                <h1>Listen to music.  Together.</h1>


                <?php

if(isset($_POST['Submit'])){

    echo '<p>Your room is ready:<br>
          <a href="http://45.56.101.195/?id=' . $id . '">http://45.56.101.195/?id=' . $id . '</a></p>';


    //echo '<p><video controls="" autoplay="" name="media">
    //    <source src="http://54.152.139.27:8000/' . $id . '" type="audio/mpeg"></video>';
    //src="http://54.152.139.27:8000/' . $id . ';" type="audio/mpeg"></audio></p>';
    //  echo '"http://54.152.139.27:8000/' . $id . ';"';

}

if(isset($_GET["id"])){

    $gotId = $_GET["id"];
    echo '<p><video controls="" autoplay="" preload="auto" name="media">
                <source src="http://54.152.139.27:8000/' . $gotId . '" type="audio/mpeg"></video>';

    echo '<p>Room URL:<br>
                <a href="http://45.56.101.195/?id=' . $gotId . '">http://45.56.101.195/?id=' . $gotId . '</a></p>';

}
                ?>


                <form action="index.php" method="post" enctype="multipart/form-data">
                    <input name="theFile" type="file" />
                    <input name="Submit" type="submit" value="Upload">
                </form>
            </div>

        </body>

        </html>
