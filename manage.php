<?php

//Starts session and makes sure user is logged in.
session_start();
include "UserManager.php";
UserManager::checkLogin();

//Set up the music manager
include "MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$databaseConnected = $manager->connectToDatabase();

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
            function upload_started_lower(){
              document.getElementById("loading2").style.display="block";

            }
            function upload_completed(){
             document.getElementById("upload_status").style.display="block";
             document.getElementById("loading").style.display="none";

            }
            function upload_fail(){
              document.getElementById("upload_fail").style.display="block";

            }
            
            function edit_success(){
              document.getElementById("edit_success").style.display="block";

            }
            function delete_success(){
              document.getElementById("delete_success").style.display="block";

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

            <!--<div class="alert alert-success alert-dismissible" role="alert" id="upload_status" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song Uploaded!</div>!-->
            <span id="headerAlert"></span>
            <div class="alert alert-warning alert-dismissible" role="alert" id="upload_fail" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Upload failed.</div>
             <div class="alert alert-success alert-dismissible" role="alert" id="edit_success" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song edited.</div>
            <div class="alert alert-success alert-dismissible" role="alert" id="delete_success" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song deleted.</div>

            <?php
            // get the edited tags if they present

            if(isset($_GET['edited'])){
                
                $value = $_GET['edited'];
                
                if($value == '1'){
                    ?>
                    <script>
                    edit_success();
                    </script>
                <?php
                }
                
            }

            // get the deleted tag if its present
            if(isset($_GET['deleted'])){
                
                $value = $_GET['deleted'];
                
                if($value == '1'){
                    ?>
                    <script>
                    delete_success();
                    </script>
                <?php
                }
                
            }
            ?>


         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Manage Music</h3>
          </div>
          <div class="panel-body">
            You can edit and delete the songs you have previously uploaded here.
              <p></p>
              <div class="list-group" style="max-height:300px; overflow:auto;">
              
		<?php
		$result = $manager->searchSongs(null, null);
		//song_id
		while($row = $result->fetch_assoc())
		{
            $the_id = $row['song_id'];
            $art = $row['artist'];
            $tit = $row['title'];
            $rat = $row['rating'];
            
		echo '<a href="javascript:;" id="' . $the_id . '" title="' . $art . ' - ' . $tit . '" class="list-group-item"><b>'. $row['artist'] . '</b> - ' . $row['title'] . '<br>Rating: ' . $rat . '</a>';
		}
		?>
                  <script>
                      var s_id = 0;
                      // this tiny script handles getting the id from the table
                  $(".list-group-item").click(function(){
                      //console.log(this.id);
                      // s_id = this.id;
                      // console.log(s_id);
                      $('#edit').attr('data-target','#e');
                      $('#delete').attr('data-target','#d');


                    });
                  </script>

            </div>
            <a href="manage.php" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-refresh"></span> Update Collection</a>
              <a href="#" id="edit" data-toggle="modal" data-target="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
              <a href="#" id="delete" data-toggle="modal" data-target="#" class="btn btn-primary btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a>
          </div>
        </div>
            
            <!-- edit modal !-->
            <div class="modal fade" id="e">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Song</h4>
                  </div>
                  <div class="modal-body" id="cont">
                    <script>
                         $(".list-group-item").click(function(){
                            //console.log(this.title)
                             var titsplit = "";
                             var artsplit = "";
                             artsplit = this.title.substring(0, this.title.indexOf(" - "));
                             titsplit = this.title.substring(this.title.indexOf(" - ")+3, this.title.length);

                             
                             $("#sedit").val(artsplit);
                            $("#tedit").val(titsplit);
                             $("#idedit").val(this.id);

                             
                         });
                        </script>
                        
                        <form action="editSong.php" method="post">
                         <!-- Table -->
                      <table class="table" style="width:100%">
                    <tbody>
                        <tr>
                            <td><b>Artist: </b><input type="text" name="EditArtist" class="form-control" id="sedit" value="Song Artist" aria-describedby="basic-addon1"></td>
                        </tr>
                        <tr>
                            <td><b>Title:</b><input type="text" name="EditTitle" class="form-control" id="tedit" value="Song Title" aria-describedby="basic-addon1">
                            </td>
                        </tr>
                        
                          </tbody>
                            </table>
                            <!-- hidden id !-->
                            <input type="text" name="EditSongId" style="display:none;" class="form-control" id="idedit" aria-describedby="basic-addon1">
                      
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
		</form>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        
            
            
            <!-- delete modal !-->
            <div class="modal fade" id="d">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Song</h4>
                  </div>
                  <div class="modal-body" id="cont">
                    <p><script>
                         $(".list-group-item").click(function(){
                            var titsplitd = "";
                             var artsplitd = "";
                             artsplitd = this.title.substring(0, this.title.indexOf(" - "));
                             titsplitd = this.title.substring(this.title.indexOf(" - ")+3, this.title.length);
                             

                             $("#deleteString").html("Are you sure you want to delete \"" + artsplitd + " - " + titsplitd + "\"?");
                             $("#iddelete").val(this.id);

                             
                         });
                        </script>
                    <form action="deleteSong.php" method="post">
                        <input type="text" name="DeleteId" style="display:none;" class="form-control" id="iddelete" aria-describedby="basic-addon1">

                         <!-- Table -->
                      <div id="deleteString">Are you sure you want to delete "Artist - Song Title"?</div>
                      
                      </p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
		</form>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


        <div id="loading" style="display:none;"><center><b>Uploading...</b><br><img src="img/loader.gif"/></center><br></div>


         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Upload Music</h3>
          </div>
          <div class="panel-body">
            Upload songs here that you might want to use in a playlist.
            <p></p>
            <?php

            //error_reporting(E_ALL);
          //  ini_set( 'display_errors', 'On');// Turn on debugging.

            // requires and includes
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

                $id = uniqid();// generate unique id for the room

                //retreive post variables
                $fileName = $_FILES['theFile']['name'];
                $fileTempName = $_FILES['theFile']['tmp_name'];
                
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

                    $awsFileName = time();


                    if($_FILES['theFile']['error'] > 0){
                        echo "return code: " . $_FILES['theFile']['error'];

                        if($_FILES['theFile']['error'] == 4){
                            echo ' No file was uploaded.  Is it a music file?';
                        }
                    }
                    try{
                    $result = $aws->uploadSong($s3Client, $fileTempName, $awsFileName);// Upload the file to AWS

		    $locationString = "https://user-music-folder.s3.amazonaws.com/Music/" . $awsFileName . ".mp3";

		    $debug = $manager->addSong($songTitle, $songArtist, null, null, $locationString);
              
             if(strlen($songArtist) < 1 || strlen($songTitle) < 1){
                    
                    // set the titles to unknown so they aren't null in the database
                    $timestamp = time();
                    $songArtist = "Unknown Artist " . $timestamp;
                    $songTitle = "Unknown Title " . $timestamp;

                         ?>
              
                 <script>

                  $('#headerAlert').html('<div class="alert alert-success alert-dismissible" role="alert" id="upload_status2" style="display:block;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song uploaded!<br>We couldn\'t figure out the song title and artist for this song, you might want to edit it below.</div>');


                  </script>
              <?php
                    // WHEN YOU UPLOAD THIS TO THE DATABASE CALL IT UNKNOWN OR THE DATE MAYBE?
                }
                else {
                    
                    ?>
                 <script>

                  $('#headerAlert').html('<div class="alert alert-success alert-dismissible" role="alert" id="upload_status2" style="display:block;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song uploaded!<br>We think the song is <b><?php echo $songTitle . "</b> by <b>" . $songArtist; ?></b>. If that doesn\'t look right you can edit it below.</div>');


                  </script>
              <?php
                }
			

       
                  }
                  catch(Exception $e){
                    echo '<script>upload_fail();</script>';

                  }
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
                else {
                  echo '<script>upload_fail();</script>';
                }

            }
              ?>

                <form action="manage.php" method="post" enctype="multipart/form-data" onsubmit="upload_started()">
                    <input name="theFile" type="file" />
                    <input name="Submit" type="submit" value="Upload" id="actualSubmit" value="Upload" style="display:none;">
                </form>
        <p></p>
        <button class="btn btn-primary btn-primary" id="uploadreg"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</a>
      </div>
    </div>
    <div id="loading2" style="display:none;"><center><b>Uploading...</b><br><img src="img/loader.gif"/></center><br></div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Upload + Transcode Music</h3>
      </div>
      <div class="panel-body">
        Upload non-mp3 songs here that you might want to use in a playlist. We will convert it to mp3 for you!
        <p></p>

        <?php

            //include ('template/aws.php');// Include our aws services
            include ('template/ElasticTranscoderJob.php');
            //include ('template/util.php');// Utility class for generating liquidsoap files

            $aws = new AWS();
            $s3Client = $aws->authS3();// Authorize the S3 object.

            $bucket = $aws->getBucket();// Get the bucket name for our music uploads


            //check whether a form was submitted
            if(isset($_POST['Transcode'])){
				
				// id3 information
                // Initialize getID3 engine
                $getID3 = new getID3;

                $id = uniqid();// generate unique id for the room

                //retreive post variables
                $fileName = $_FILES['theFile']['name'];
                $fileTempName = $_FILES['theFile']['tmp_name'];
				
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
					
					?>
   
              <?php
                    // WHEN YOU UPLOAD THIS TO THE DATABASE CALL IT UNKNOWN OR THE DATE MAYBE?
                }
                else {
                    
                    ?>
              <?php
                }
                    
				
                if(!preg_match("/\.(mp3)$/", $fileName)){

                    $awsFileName = time();


                    try{
                      $result = $aws->uploadSong($s3Client, $fileTempName, $awsFileName);// Upload the file to AWS

					   $locationString = "https://user-music-folder.s3.amazonaws.com/Music/" . $awsFileName . ".mp3";

		    $debug = $manager->addSong($songTitle, $songArtist, null, null, $locationString);
			
					  
                        if(strlen($songArtist) < 1 || strlen($songTitle) < 1){
                            
                            $songArtist = "Unknown Artist " . $timestamp;
                            $songTitle = "Unknown Title " . $timestamp;
                    
                    // set the titles to unknown so they aren't null in the database
                    $timestamp = time();

					
					?>
                 <script>

                  $('#headerAlert').html('<div class="alert alert-success alert-dismissible" role="alert" id="upload_status2" style="display:block;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song uploaded!<br>We couldn\'t figure out the song title and artist for this song, you might want to edit it below.</div>');


                  </script>
              <?php
                    // WHEN YOU UPLOAD THIS TO THE DATABASE CALL IT UNKNOWN OR THE DATE MAYBE?
                }
                else {
                    
                    ?>
                 <script>

                  $('#headerAlert').html('<div class="alert alert-success alert-dismissible" role="alert" id="upload_status2" style="display:block;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Song uploaded!<br>We think the song is <b><?php echo $songTitle . "</b> by <b>" . $songArtist; ?></b>. If that doesn\'t look right you can edit it below.</div>');


                  </script>
              <?php
                }
                        
                      echo '<script>upload_completed();</script>';
                    }
                    catch(Exception $e2)
                    {
                      echo '<script>upload_fail();</script>';

                    }
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
                else{
                  echo '<script>upload_fail();</script>';

                }

            }
              ?>

                <form action="manage.php" method="post" enctype="multipart/form-data" onsubmit="upload_started_lower()">
                    <input name="theFile" type="file" />
                    <input name="Transcode" type="submit" value="Upload + Transcode" id="actualTranscode" style="display:none;">
                </form>
        <p></p>
        <button class="btn btn-primary btn-primary" id="transcodereg"><span class="glyphicon glyphicon-cloud-upload"></span> Upload + Transcode</a>
      </div>
    </div>


    </body>

    <script>
    $("#uploadreg").click(function(){
      $("#actualSubmit").click();
    });

    $("#transcodereg").click(function(){
      $("#actualTranscode").click();
    });
    </script>



</html>
