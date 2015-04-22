<?php

//Starts session and makes sure user is logged in.
session_start();
include "UserManager.php";
UserManager::checkLogin();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Harmony - Manage Music</title>
        <head>

            <!-- css imports !-->
            <?php include ('template/head.php'); ?>

        </head>

        <body>
            
        <script type="text/javascript">
            function upload_started(){
             document.getElementById("loading").style.display="block";
            }
            function upload_completed(){
             document.getElementById("upload_status").style.display="block";
             document.getElementById("loading").style.display="none";

            }
        </script>
            <a name="top"></a> 
            
            <!-- css/html nav !-->
            <!-- check for login before we draw the nav !-->
            <?php include ('template/nav.php') ?>
            
            <?php 
                // Find out whether or not the user is logged in and pass it into Nav
                $nav = new Nav(true); 
                $nav->render();
            ?>
            
            
            <?php

            error_reporting(E_ALL);


            ini_set( 'display_errors', 'On');// Turn on debugging.

            //include ('template/aws.php');// Include our aws services
            //include ('template/util.php');// Utility class for generating liquidsoap files
            ?>
            
            <div class="alert alert-success alert-dismissible" role="alert" id="upload_status" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song Uploaded!</div>
            <span id="headerAlert"></span>
            
         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Manage Music</h3>
          </div>
          <div class="panel-body">
            You can edit and delete the songs you have previously uploaded here.
              <p></p>
              <div class="list-group" style="max-height:300px; overflow:auto;">
              <a href="#" class="list-group-item">Artist1 - Title</a>
              <a href="#" class="list-group-item">Artist2 - Title</a>
              <a href="#" class="list-group-item">Artist3 - Title</a>
              <a href="#" class="list-group-item">Artist4 - Title</a>
              <a href="#" class="list-group-item">Artist1 - Title</a>
              <a href="#" class="list-group-item">Artist2 - Title</a>
              <a href="#" class="list-group-item">Artist3 - Title</a>
              <a href="#" class="list-group-item">Artist4 - Title</a>
            </div>
              <a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
              <a href="#" class="btn btn-primary btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a>
          </div>
        </div>
                        
            
        <div id="loading" style="display:none;"><center><b>Uploading...</b><br><img src="img/loader.gif"/></center><br></div>

            
         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Upload Music</h3>
          </div>
          <div class="panel-body">
            Upload songs here that you might want to use in a playlist.
            <p></p>
              <?php

            error_reporting(E_ALL);


            ini_set( 'display_errors', 'On');// Turn on debugging.

            //include ('template/aws.php');// Include our aws services
            //include ('template/util.php');// Utility class for generating liquidsoap files
            ?>
   
            
                  
                <?php

                ini_set( 'display_errors', 'On');// Turn on debugging.

                include ('template/aws.php');// Include our aws services
                include ('template/util.php');// Utility class for generating liquidsoap files

                $aws = new AWS();
                $s3Client = $aws->authS3();// Authorize the S3 object.

                $bucket = $aws->getBucket();// Get the bucket name for our music uploads


                //check whether a form was submitted
                if(isset($_POST['Submit'])){
                    

                    $id = uniqid();// generate unique id for the room

                    //retreive post variables
                    $fileName = $_FILES['theFile']['name'];
                    $fileTempName = $_FILES['theFile']['tmp_name'];
                    
                    if(preg_match("/\.(mp3)$/", $fileName)){
                        
                        $awsFileName = time();


                        if($_FILES['theFile']['error'] > 0){
                            echo "return code: " . $_FILES['theFile']['error'];

                            if($_FILES['theFile']['error'] == 4){
                                echo ' No file was uploaded.  Is it a music file?';
                            }
                        }

                        $result = $aws->uploadSong($s3Client, $fileTempName, $awsFileName);// Upload the file to AWS

                        echo '<script>upload_completed();</script>';
                        //$util = new Util();

                        // Generate a new .pls on the Linode server
                        // This contains the mp3 urls on S3.
                        //$util->makePlaylistFile($result, $id);

                        // make the pls file that we won't be using to make the playlist
                        //$util->makePlaylistFileFull($result, $id);

                        // Generate a new .liq files on the Linode Server
                        // This sends data to the Icecast server to be streamed.
                        //$util->makeLiqFile($id);

                        // Run the liquidsoap script on the server
                        //$util->runLiqScript($id);
                        
                    }
        
                }              
                  ?>
              
                    <form action="manage.php" method="post" enctype="multipart/form-data" onsubmit="upload_started()">
                        <input name="theFile" type="file" />
                        <input name="Submit" type="submit" value="Upload">
                    </form>
              
            <!--<a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</a>!-->
          </div>
        </div>
		
		<div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Upload + Transcode Music</h3>
          </div>
          <div class="panel-body">
            Upload non-mp3 songs here that you might want to use in a playlist. We will convert it to mp3 for you!
            <p></p>
			
			<?php

                ini_set( 'display_errors', 'On');// Turn on debugging.

                //include ('template/aws.php');// Include our aws services
				include ('template/ElasticTranscoderJob.php');
                //include ('template/util.php');// Utility class for generating liquidsoap files

                $aws = new AWS();
                $s3Client = $aws->authS3();// Authorize the S3 object.

                $bucket = $aws->getBucket();// Get the bucket name for our music uploads


                //check whether a form was submitted
                if(isset($_POST['Transcode'])){
                    

                    $id = uniqid();// generate unique id for the room

                    //retreive post variables
                    $fileName = $_FILES['theFile']['name'];
                    $fileTempName = $_FILES['theFile']['tmp_name'];
                    
                    if(!preg_match("/\.(mp3)$/", $fileName)){
                        
                        $awsFileName = time();


                        if($_FILES['theFile']['error'] > 0){
                            echo "return code: " . $_FILES['theFile']['error'];

                            if($_FILES['theFile']['error'] == 4){
                                echo ' No file was uploaded.  Is it a music file?';
                            }
                        }

                        $result = $aws->uploadSong($s3Client, $fileTempName, $awsFileName);// Upload the file to AWS

                        echo '<script>upload_completed();</script>';
                        //$util = new Util();

                        // Generate a new .pls on the Linode server
                        // This contains the mp3 urls on S3.
                        //$util->makePlaylistFile($result, $id);

                        // make the pls file that we won't be using to make the playlist
                        //$util->makePlaylistFileFull($result, $id);

                        // Generate a new .liq files on the Linode Server
                        // This sends data to the Icecast server to be streamed.
                        //$util->makeLiqFile($id);

                        // Run the liquidsoap script on the server
                        //$util->runLiqScript($id);
						
						/************************************
						 *          TRANSCODE IT
						 ***********************************/
                        $key = $awsFileName.'.mp3';
						$job = new ElasticTranscoderJob($key,$key);
                    }
        
                }              
                  ?>
              
                    <form action="manage.php" method="post" enctype="multipart/form-data" onsubmit="upload_started()">
                        <input name="theFile" type="file" />
                        <input name="Transcode" type="submit" value="Upload + Transcode">
                    </form>
			
            <!--<a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-cloud-upload"></span> Upload + Transcode</a>-->
          </div>
        </div>
                

        </body>
        


</html>
