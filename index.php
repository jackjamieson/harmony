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

    <!-- nav css/html nav !-->
    <?php include ('template/nav.php') ?>


      <?php

      error_reporting(E_ALL);
      ini_set( 'display_errors', 'On');// Turn on debugging.

      include ('template/aws.php');// Log in to AWS

      $bucket = "com.cloud.php.data";




      //check whether a form was submitted
      if(isset($_POST['Submit'])){

        $id = uniqid();

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

        $result = $s3Client->putObject(array(
          'ACL' => 'public-read',
          'SourceFile' => $fileTempName,
          'Bucket' => "com.cloud.php.data",
          'Key' => "Music/" . $awsFileName . ".mp3"
        ));

        // create the initial playlist finial
        $playlistUrls = $result['ObjectURL'] . "\r\n";

        $pls = fopen('liq/' . $id . "-playlist.pls", "w") or die("Unable to write!");
        fwrite($pls, $playlistUrls);
        fclose($pls);


        //create the initial liquidsoap file
        $liqText =
        '
        output.icecast(%mp3,
        host = "54.152.139.27", port = 8000,
        password = "cloudphp2015", mount = "' . $id . '",
        mksafe(playlist(reload_mode="watch", "liq/' . $id . '-playlist.pls")))
        ';

        $liqFile = fopen('liq/' . $id . ".liq", "w");
        fwrite($liqFile, $liqText);
        fclose($liqFile);

        //echo 'liquidsoap /var/www/html/liq/' . $id . '.liq';
        shell_exec('nohup liquidsoap liq/' . $id . '.liq > /dev/null & echo $!');
        //var_dump($output);



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
