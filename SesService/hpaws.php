<?php

//This script allows for authorization of SES client without hard-coding credentials

//require __DIR__ . '/../vendor/autoload.php';// Load the dependencies from Composer
include 'vendor/autoload.php';
use Aws\Ses\SesClient;

class hpaws {

    //private $bucketName = "hpaws";
    private $key = "aws_access_key";
    private $secret = "aws_secret_key";

    public function __construct(){
        
        $creds = parse_ini_file('creds.ini', false);// Read the aws creds from a file on the server
        
        $this->key = $creds["aws_access_key_id"];
        $this->secret = $creds["aws_secret_access_key"];

    }

    public function authSES(){

        $client = SesClient::factory(array(
        'key' => $this->key,
        'secret' => $this->secret,
        'region' => 'us-west-2'
        ));

        return $client;

    }
}

?>
