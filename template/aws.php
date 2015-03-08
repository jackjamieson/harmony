<?php

require __DIR__ . '/../vendor/autoload.php';// Load the dependencies from Composer
use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class AWS {

    private $bucketName = "user-music-folder";
    private $key = "ACCESS_KEY";
    private $secret = "SECRET_ACCESS_KEY";

    public function __construct(){

    }

    public function authS3(){

        $s3Client = S3Client::factory(array(
            'credentials' => array(
                'key'    => $key,
                'secret' => $secret,
            )
        ));

        return $s3Client;

    }
    
    public function authElasticTranscoder(){

        $ETClient = ElasticTranscoderClient::factory(array(
        	'key' => $key,
		'secret' => $secret,
		'region' => 'us-west-2'
        ));

        return $ETClient;

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
