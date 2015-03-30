<?php

class Util {

    public function makePlaylistFile($awsS3UploadResult, $id){

        $result = $awsS3UploadResult;

        // create the initial playlist finial
        $playlistUrls = $result['ObjectURL'] . "\r\n";

        $pls = fopen('liq/' . $id . "-playlist.pls", "w") or die("Unable to write!");
        fwrite($pls, $playlistUrls);
        fclose($pls);
    }

    public function makeLiqFile($id){

        //create the initial liquidsoap file
        $liqText =
        '
        set("server.telnet", true)

        tracks = playlist("liq/' . $id . '-playlist.pls")

        radio = fallback([request.queue(id="' . $id . '"), tracks])

        output.icecast(%mp3,
        host = "54.152.139.27", port = 8000,
        password = "cloudphp2015", mount = "' . $id . '",
        mksafe(radio))
        ';

        $liqFile = fopen('liq/' . $id . ".liq", "w");
        fwrite($liqFile, $liqText);
        fclose($liqFile);
    }

    public function runLiqScript($id){
            // timelimit is install on the Linode.  It tells the script to terminate after a set time
            // right now 300 is 5 minutes, just for testing.  Should probably be a couple hours or something
            shell_exec('timelimit -p -q -t 600 nohup liquidsoap liq/' . $id . '.liq > /dev/null & echo $!');

    }

    public function updatePlaylistFile($awsS3UploadResult, $id){

      $result = $awsS3UploadResult;

      // update the initial playlist
      $playlistUrls = $result['ObjectURL'] . "\r\n";

      $pls = fopen('liq/' . $id . "-playlist.pls", "a") or die("Unable to write!");
      fwrite($pls, $playlistUrls);
      fclose($pls);

    }

    public function queueSong($awsS3UploadResult, $id) {

      $result = $awsS3UploadResult;

      $socket = fsockopen("localhost", "1234", $errno, $errstr);

      if($socket)
      {
        echo "Connected";
      }
      else {
        echo "Connection failed";
      }

      fputs($socket, "help");

      $buffer = "";

      while(!feof($socket))
      {
          $buffer .=fgets($socket, 4096);
      }

      print_r($buffer);
      echo "<br /><br /><br />";
      var_dump($buffer);


      fclose($socket);


    }



}


?>
