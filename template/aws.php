<?php

require __DIR__ . '/../vendor/autoload.php';// Load the dependencies from Composer
use Aws\S3\S3Client;

class AWS {

    private $bucketName = "user-music-folder";

    public function __construct(){

    }

    public function authS3(){

        $s3Client = S3Client::factory(array(
            'credentials' => array(
                'key'    => 'AKIAIO7OMT7GA7D4I7AQ',
                'secret' => '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',
            )
        ));

        return $s3Client;

    }

    public function uploadSong($s3Client, $fileTempName, $awsFileName){

        $result = $s3Client->putObject(array(
            'ACL' => 'public-read',
            'SourceFile' => $fileTempName,
            'Bucket' => $this->bucketName,
            'Key' => "Music/" . $awsFileName . ".mp3"
        ));
        
        return $result;

    }


    public function getBucket(){
        
        return $this->bucketName;
    }
}

?>
