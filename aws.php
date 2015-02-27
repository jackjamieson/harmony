<?php



require __DIR__ . '/vendor/autoload.php';// Load the dependencies from Composer
use Aws\S3\S3Client;

//Set up the S3 client to be used in the rest of the application.
$s3Client = S3Client::factory(array(
    'credentials' => array(
        'key'    => 'AKIAJTOD5VOFVD32ZCNQ',
        'secret' => '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',
    )
));



?>