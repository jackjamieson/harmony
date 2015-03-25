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
                <?php
                // if the url has an id query then use that to find the appropriate stream
                if(isset($_GET["id"])){
                      

                    $gotId = $_GET["id"];
                    echo '<h3>Room ' . $gotId . '</h3>';// write out the room's name
                    
                    // draw the audio player and populate it with the appropriate stream
                    echo '<p><audio controls="" autoplay="" preload="auto" name="media">
                                <source src="http://54.152.139.27:8000/' . $gotId . '" type="audio/mp3"></audio>';

                    echo '<div class="well"><b>Share Room URL:</b><br>
                                <a href="http://45.56.101.195/room.php?id=' . $gotId . '">http://45.56.101.195/room.php?id=' . $gotId . '</a></div>';
                    
                    echo '<!-- Single button -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Add Song <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="#" class="upload-new">Upload New</a></li>
                                <li><a href="#">From Library</a></li>
                              </ul>
                            </div>
                            
                            <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Upvote</button>
                            <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Downvote</button>
                            <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Leave Room</button>


                            ';

                }
                ?>
                  
              </div>
            </div> 
            </div>
             
             
          <div class="col-xs-6 col-md-4">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Chat</h3>
              </div>
              <div class="panel-body">
                Chatroom could go here probably.
              </div>
            </div> 
             
             </div>
        </div>
               
        <!-- modal boxes for adding to the playlist !-->
        <script>
        $(document).ready(function(){
            $('.upload-new').paulund_modal_box();
        });
        </script>
        </body>

</html>
