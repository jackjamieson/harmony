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


  <title>Harmony - Music Room</title>
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


    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Music</h3>
          </div>
          <div class="panel-body">
              <!-- script to run to php the read the playlist !-->

            <?php
            // if the url has an id query then use that to find the appropriate stream
            if(isset($_GET["id"])){
                
                $gotId = $_GET["id"];

                echo 
                '
                <script type="text/javascript">
                function recp() {
                  $("#playlist").load("template/readPlaylist.php?id=' . $gotId . '");
                }
                function getCurr(){
                  $("#song").load("template/getCurrentSong.php?id=' . $gotId . '");

                }
                setInterval(recp,5000);
                setInterval(getCurr,5000);


                </script>
                ';


            echo '<h3>Room ' . $gotId . '</h3>';// write out the room's name

                // display the playlist
                echo '<div class="well"><h4><b>Now Playing:</b><p><div id="song"></div></h4>
                <p><b>Playlist:</b><br><div id="playlist" style="white-space:pre"></div></div>';
                
              // draw the audio player and populate it with the appropriate stream
              echo '<p><audio autoplay="" controls="controls" preload="auto" name="media">
                <source src="http://54.152.139.27:8000/' . $gotId . '" type="audio/mp3"></audio>';

                  echo '<div class="well"><b>Share Room URL:</b><br>
                    <a href="http://45.56.101.195/room.php?id=' . $gotId . '">http://45.56.101.195/room.php?id=' . $gotId . '</a></div>';

                    echo '<!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Add Song <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#" data-toggle="modal" data-target="#upload-new">Upload New</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#add-lib">From Library</a></li>
                      </ul>
                    </div>

                    <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Upvote</button>
                    <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Downvote</button>
                    <a href="manage.php"><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Leave Room</button></a>


                    ';

                  }
                  ?>

                </div>
              </div>
            </div>

            <!-- Modal upload new -->
            <div class="modal fade" id="upload-new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload New Song</h4>
                  </div>
                  <div class="modal-body">
                    <div class="alert alert-success" role="alert" id="upload_status" style="display:none;">Song successfully added!</div>
                    <div class="alert alert-warning" role="alert" id="failure" style="display:none;">Song must be an MP3! Try transcoding it first.</div>

                    <div id="loading" style="display:none;"><center><b>Uploading...</b><br><img src="img/loader.gif"/></center><br></div>

                    Add to the playlist:<p>
                    <script type="text/javascript">
                    function upload_started(){
                     document.getElementById("upload_status").style.display="none";
                    document.getElementById("loading").style.display="block";

                    }
                    function upload_completed(){
                     document.getElementById("upload_status").style.display="block";
                    document.getElementById("loading").style.display="none";

                    }
                        
                    function upload_failed(){
                        document.getElementById("failure").style.display="block";   
                        document.getElementById("loading").style.display="none";

                    }

                    var close = document.getElementById("closed");

                    function reset() {
                      document.getElementById("upload_status").style.display="none";
                        document.getElementById("failed").style.display="none";   


                    }

                    close.onclick = reset;
                    </script>
                    </p>
                    <form action="upload_in_room.php?id=<?php echo $gotId; ?>" method="post" enctype="multipart/form-data" target="hidden_upload" onsubmit="upload_started()">
                        <input name="theFile" type="file" />
                        <input name="Submit" type="submit" value="Upload">
                    </form>
                    <!-- upload the file to a hidden iframe so we don't have to reload the page !-->
                    <iframe id="hidden_upload" name="hidden_upload" style="display:none" ></iframe>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="closed" onclick="reset()">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal add from library -->
            <div class="modal fade" id="add-lib" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Select Song from Library</h4>
                  </div>
                  <div class="modal-body">
                    Add to the playlist:<p>

                    </p>
                    Select from your library...
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>


            <div class="col-xs-6 col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Chat</h3>
                </div>
                <div class="panel-body">
                  <?php
                  $colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
                  $user_colour = array_rand($colours);
                  ?>

                  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
                  <script>
                  var users = []; // global array
                  </script>

                  <script language="javascript" type="text/javascript">
                  $(document).ready(function(){

                    var scrolled = 0;
                    var divRID = getQueryVariable("id");
                    document.getElementById("rid").innerHTML = "";

                    //create a new WebSocket object.
                    var wsUri = "ws://45.56.97.42:9000/server.php";
                    websocket = new WebSocket(wsUri);

                    websocket.onopen = function(ev) { // connection is open
                      $('#message_box').append("<div class=\"system_msg\">Connected!</div>"); //notify user
                      $('#user_list').append("Connected Users:");
                    }

                    $('#send-btn').click(function(){ //use clicks message send button
                        $('#message_box').scrollTop($('#message_box').scrollTop()+40);

                      var mymessage = $('#message').val(); //get message text
                      var myname = $('#name').val(); //get user name
                      var roomID = getQueryVariable("id");

                      if(myname == ""){ //empty name?
                        alert("Enter your name please!");
                        return;
                      }
                      if(mymessage == ""){ //emtpy message?
                        alert("Enter Some message Please!");
                        return;
                      }

                      //prepare json data
                      var msg = {
                        message: mymessage,
                        name: myname,
                        color : '<?php echo $colours[$user_colour]; ?>',
                        room : roomID
                      };
                      //convert and send data to server
                      websocket.send(JSON.stringify(msg));
                    });
                      
                      $('#message').on("keypress", function(e) {
                                if (e.keyCode == 13) {
                                    scrolled=scrolled+300;

                                    $("#message_box").animate({
                                            scrollTop:  scrolled
                                       });

                                
                      var mymessage = $('#message').val(); //get message text
                      var myname = $('#name').val(); //get user name
                      var roomID = getQueryVariable("id");

                      if(myname == ""){ //empty name?
                        alert("Enter your name please!");
                        return;
                      }
                      if(mymessage == ""){ //emtpy message?
                        //alert("Enter Some message Please!");
                        return;
                      }

                      //prepare json data
                      var msg = {
                        message: mymessage,
                        name: myname,
                        color : '<?php echo $colours[$user_colour]; ?>',
                        room : roomID
                      };
                      //convert and send data to server
                      websocket.send(JSON.stringify(msg));
                                }
                        });

                    //#### Message received from server?
                    websocket.onmessage = function(ev) {
                      var msg = JSON.parse(ev.data); //PHP sends Json data
                      var type = msg.type; //message type
                      var umsg = msg.message; //message text
                      var uname = msg.name; //user name
                      var ucolor = msg.color; //color
                      var uroomid = msg.room; // room id
                      var foundMatch = "NO";

                      var gatherID = getQueryVariable("id");
                      if(uroomid === gatherID)
                      {
                        if(type == 'usermsg')
                        {
                          $('#message_box').append("<div><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");

                          //This loops will find a username match within the user array
                          for(i = 0; i < users.length + 1; i++)
                          {
                            if(users[i] == uname)
                            {
                              foundMatch = "YES";
                            }
                          }
                          //document.getElementById("user_list").innerHTML = users.toString() + "yo"; //debugging

                          //if not match is found, then display the user name only once
                          if(foundMatch == "NO")
                          {
                            users.push(uname); //push a new user into the array
                            $('#user_list').append("<div><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+uname+"</span></div>");
                          }
                        }
                      }
                      if(type == 'system')
                      {
                        //$('#message_box').append("<div class=\"system_msg\">"+umsg+"</div>");
                      }

                      $('#message').val(''); //reset text
                    };

                    websocket.onerror	= function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
                    websocket.onclose 	= function(ev){$('#message_box').append("<div class=\"system_msg\">Connection Closed</div>");};
                  });

                  function getQueryVariable(variable)
                  {
                    var query = window.location.search.substring(1);
                    var vars = query.split("?");
                    for (var i=0;i<vars.length;i++) {
                      var pair = vars[i].split("=");
                      if(pair[0] == variable){return pair[1];}
                    }
                    return(false);
                  }
                  </script>
                  <div id="rid"></div>
                  <div class="chat_wrapper">
                    <div class="message_box" id="message_box" style="height:200px; overflow:auto;"></div>
                    
                  </div>
                    <div class="panel">
                      <input type="text" disabled="" name="name" id="name" value="<?php echo $_SESSION['Username'] ?>" maxlength="10" style="width:20%"  />
                      <input type="text" name="message" id="message" placeholder="Message" maxlength="80" style="width:60%" />
                      <button id="send-btn">Send</button>
                    </div>
                  <div id="users"></div>

<!--
                  <div class = "chat_wrapper2">
                    <div class = "user_list" id = "user_list"></div>
                  </div>
-->
                </div>

              </div>
            </div>

          </body>

          </html>
