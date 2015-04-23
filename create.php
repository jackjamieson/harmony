<?php
include "base.php"; //Handles session start and database interaction.

//If LoggedIn not set, return to home screen.
if(empty($_SESSION['LoggedIn']))
{
	header('Location: index.php');
	die();
}

?>

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
            <div class="alert alert-success" role="alert" id="status" style="display:none;">Email sent!</div>
            <span id="headerAlert"></span>
            <div id="loading" style="display:none;"><center><b>Uploading...</b><br><img src="img/loader.gif"/></center><br></div>

            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Create Room</h3>
              </div>
              <div class="panel-body">
                <span id="header">Start a room by uploading a new song or selecting one of your previously uploaded tracks.</span>


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

                        $util = new Util();

                        // Generate a new .pls on the Linode server
                        // This contains the mp3 urls on S3.
                        $util->makePlaylistFile($result, $id);

                        // make the pls file that we won't be using to make the playlist
                        $util->makePlaylistFileFull($result, $id);

                        // Generate a new .liq files on the Linode Server
                        // This sends data to the Icecast server to be streamed.
                        $util->makeLiqFile($id);

                        // Run the liquidsoap script on the server
                        $util->runLiqScript($id);

                    }
                    else {




                    }





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
                    <form action="create.php" method="post" enctype="multipart/form-data" onsubmit="upload_started()">
                        <input name="theFile" type="file" />
                        <input name="Submit" type="submit" value="Upload" id="actualUpload" style="display:none;">
                    </form>
										<p></p>
										<button class="btn btn-primary btn-primary" id="uploadreg"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</a>

                  </div>
                </div>

                <p></p>
                <div class="panel panel-default">
                <div class="panel-body">
                    <b>Select a previously uploaded song:</b>

                  </div>
                </div>

                <script type="text/javascript">
                    function upload_started(){
                     document.getElementById("loading").style.display="block";
                    }
                    function upload_completed(){
                     document.getElementById("upload_status").style.display="none";
                    }

                    var close = document.getElementById("closed");

                    function reset() {
                      document.getElementById("upload_status").style.display="none";


                    }

                    close.onclick = reset;
                </script>';

            }
            else {

                //display room id url
                if(preg_match("/\.(mp3)$/", $fileName)){
                    ?>
                  <script>
                    $('#header').html('Your room has been created, send the link to your friends and make some sweet music.');

                  </script>

                  <?php

                echo '

                <p></p>
                <div class="panel panel-default">
                <div class="panel-body">
                    <b>Room Created:</b><p></p>
                    <p>Your room is ready:<br>
          <a href="http://45.56.101.195/room.php?id=' . $id . '">http://45.56.101.195/room.php?id=' . $id . '</a></p>
                  </div>
                </div>';

                // sending emails happens below

            echo '<p></p>
            <div class="panel panel-default">
            <div class="panel-body">
            <b>Email Room URL to Friends:</b><p></p>
            <p><form action="ses_test.php" method="post" target="hidden_send" onsubmit="alertUser()">
                <input type="text" name="emailAddress[]">
                <input type="text" name="url" hidden="hidden" value="http://45.56.101.195/room.php?id=' . $id . '">
                <input name="btnButton" type="button" value="+" onClick="JavaScript:fncCreateElement();"><br>
                <span id="mySpan"></span>
                <br><input name="btnSubmit" type="submit" value="Submit">
            </form>

            <iframe id="hidden_send" name="hidden_send" style="display:none" ></iframe>

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

                function alertUser(){
                     document.getElementById("status").style.display="block";
                }
            </script> ';
            }
                else{
                  ?>
                  <script>
                  $('#headerAlert').html('<div class="alert alert-warning" role="alert" id="status" style="display:block;">Could not start the room!</div>');
                  $('#header').html('That file doesn\'t seem to be an MP3. Try out the transcoder on the <a href="manage.php">Manage Music</a> page to convert it!');

                  </script>
                  <?php
                }
            }


            ?>
              </div>
            </div>






        </body>

				<script>
				$("#uploadreg").click(function(){
					$("#actualUpload").click();
				});
				</script>

</html>
