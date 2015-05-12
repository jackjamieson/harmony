<?php

class Util {

    public function makePlaylistFile($awsS3UploadResult, $id){

        $result = $awsS3UploadResult;

        // create the initial playlist finial
        $playlistUrls = $result['ObjectURL'];

        $pls = fopen('liq/' . $id . "-playlist.pls", "w") or die("Unable to write!");
        fwrite($pls, $playlistUrls);
        fclose($pls);
    }
    
    // keep the full listing of songs so we can show the playlist
    public function makePlaylistFileFull($awsS3UploadResult, $id, $u_id){

        $result = $awsS3UploadResult;

        // create the initial playlist finial
        $playlistUrls = $result['ObjectURL'];

        $pls = fopen('liq/' . $id . "-playlist-full.pls", "w") or die("Unable to write!");
        fwrite($pls, $playlistUrls . ";" . $u_id);
        fclose($pls);
    }
    
	  public function makePlaylistFileNoUpload($songURL, $id){

        //$result = $awsS3UploadResult;

        // create the initial playlist finial
        $playlistUrls = $songURL;

        $pls = fopen('liq/' . $id . "-playlist.pls", "w") or die("Unable to write!");
        fwrite($pls, $playlistUrls);
        fclose($pls);
    }
    
    // keep the full listing of songs so we can show the playlist
    public function makePlaylistFileFullNoUpload($songURL, $id, $u_id){

        //$result = $awsS3UploadResult;

        // create the initial playlist finial
        $playlistUrls = $songURL;

        $pls = fopen('liq/' . $id . "-playlist-full.pls", "w") or die("Unable to write!");
        fwrite($pls, $playlistUrls . ";" . $u_id);
        fclose($pls);
    }
    
    
    public function makeLiqFile($id){

        //create the initial liquidsoap file
        $liqText =
        '
        def read () =
          result = list.hd(get_process_lines("sed -n 2p liq/' .  $id . '-playlist.pls ; sed -i 2d liq/' . $id . '-playlist.pls ; awk \'{printf \"%s \", $0}\' ' . $id . '-playlist.pls"))
          log(result)
          request.create(result)
        end

        tracks = playlist("liq/' . $id . '-playlist.pls")

        queued_songs = request.dynamic(read,length=30.0,timeout=60.0)

        radio = fallback(track_sensitive=true,[queued_songs, tracks])

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
            shell_exec('timelimit -p -t 1800 liquidsoap liq/' . $id . '.liq > /dev/null &');

    }

    public function updatePlaylistFile($awsS3UploadResult, $id){

      $result = $awsS3UploadResult;

      // update the initial playlist
      $playlistUrls = "\n" . $result['ObjectURL'];

      $pls = fopen('liq/' . $id . "-playlist.pls", "a") or die("Unable to write!");
      fwrite($pls, $playlistUrls);
      fclose($pls);

    }
    
    public function updatePlaylistFileFull($awsS3UploadResult, $id, $u_id){

      $result = $awsS3UploadResult;

      // update the initial playlist
      $playlistUrls = "\n" . $result['ObjectURL'];

      $pls = fopen('liq/' . $id . "-playlist-full.pls", "a") or die("Unable to write!");
      fwrite($pls, $playlistUrls . ";" . $u_id);
      fclose($pls);

    }
    
    public function updatePlaylistFileNoUpload($songUrl, $id){

      //$result = $awsS3UploadResult;

      // update the initial playlist
      $playlistUrls = "\n" . $songUrl;

      $pls = fopen('liq/' . $id . "-playlist.pls", "a") or die("Unable to write!");
      fwrite($pls, $playlistUrls);
      fclose($pls);

    }
    
    public function updatePlaylistFileFullNoUpload($songUrl, $id, $u_id){

      //$result = $awsS3UploadResult;

      // update the initial playlist
      $playlistUrls = "\n" . $songUrl;

      $pls = fopen('liq/' . $id . "-playlist-full.pls", "a") or die("Unable to write!");
      fwrite($pls, $playlistUrls . ";" . $u_id);
      fclose($pls);

    }
}


?>
