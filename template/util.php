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
        output.icecast(%mp3,
        host = "54.152.139.27", port = 8000,
        password = "", mount = "' . $id . '",
        mksafe(playlist(reload_mode="watch", "liq/' . $id . '-playlist.pls")))
        ';

        $liqFile = fopen('liq/' . $id . ".liq", "w");
        fwrite($liqFile, $liqText);
        fclose($liqFile);
    }
    
    public function runLiqScript($id){
            // timelimit is install on the Linode.  It tells the script to terminate after a set time
            // right now 300 is 5 minutes, just for testing.  Should probably be a couple hours or something
            shell_exec('timelimit -p -q -t 300 nohup liquidsoap liq/' . $id . '.liq > /dev/null & echo $!');

    }



}


?>
