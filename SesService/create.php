<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Harmony - Create Room</title>
        <head>

            <!-- css imports !-->
            <?php include ('template/head.php'); ?>

        </head>

        <body>

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
            
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Create Room</h3>
              </div>
              <div class="panel-body">
                Start a room by uploading a new song or selecting one of your previously uploaded tracks.
                
                  
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
                  
            <?php

            //$sesClient = new $aws->authSES();

            if(!isset($_POST['Submit'])){
                echo '
              <p></p>
                <div class="panel panel-default">
                <div class="panel-body">
                    <b>Upload a song:</b><p></p>
                    <form action="create.php" method="post" enctype="multipart/form-data">
                        <input name="theFile" type="file" />
                        <input name="Submit" type="submit" value="Upload">
                    </form>                  
                  </div>
                </div>
                  
                <p></p>
                <div class="panel panel-default">
                <div class="panel-body">
                    <b>Select a previously uploaded song:</b>
                 
                  </div>
                </div>';
                        
            }
            else {

                echo '<p></p>
                <div class="panel panel-default">
                <div class="panel-body">
                    <b>Room Created:</b><p></p>
                    <p>Your room is ready:<br>
          <a href="http://45.56.101.195/room.php?id=' . $id . '">http://45.56.101.195/room.php?id=' . $id . '</a></p>             
                  </div>
                </div>';
            

            echo '<p></p>
            <div class="panel panel-default">
            <div class="panel-body">
            <b>Email Room URL to Friends:</b><p></p>
            <p><form action="ses_test.php" method="post">
                <input type="text" name="emailAddress[]">
                <input name="btnButton" type="button" value="+" onClick="JavaScript:fncCreateElement();"><br>
                <span id="mySpan"></span>
                <br><input name="btnSubmit" type="submit" value="Submit">
            </form>
            <br>
            </div>
            </div>


            <script language="javascript"> //script for sending email
                function fncCreateElement()
                {
                    var mySpan = document.getElementById("mySpan");

                   var myElement1 = document.createElement("input");
                   myElement1.setAttribute("type","text");
                   myElement1.setAttribute("name","emailAddress[]");
                   mySpan.appendChild(myElement1); 
                
                   var myElement2 = document.createElement("br");
                   mySpan.appendChild(myElement2);
                }
            </script> ';
            }


            ?>    
              </div>
            </div>

            
            <!--This part sends email using AWS SES to multiple users-->
      

      

        </body>

</html>
