<?php

session_start(); //Important that a session is started whenever UserManager is called.
include "UserManager.php";

$user = $_POST['Username'];
$pass = $_POST['Password'];
$email = $_POST['Email'];

$manager = new UserManager();

//Unset the POST value
unset($_POST['Password']);

//If forms values left blank, redirect to homepage.
if(empty($user) || empty($pass) || empty($email))
{
	header("Location: index.php");
	die();
}

//Return to index on failed database connection. Maybe handle error?
if($manager->connectToDatabase() === FALSE)
{
	header("Location: index.php");
	die();
}


//Attempt to register user. If failure, return to index.
if($manager->registerNewUser($user, $pass, $email) === FALSE)
{
	header("Location: index.php");
	die();
}

//Success
else
{	
	//Send confirmation email to new user
	<?php

session_start(); //Important that a session is started whenever UserManager is called.
include "UserManager.php";

$user = $_POST['Username'];
$pass = $_POST['Password'];
$email = $_POST['Email'];

$manager = new UserManager();

//Unset the POST value
unset($_POST['Password']);

//If forms values left blank, redirect to homepage.
if(empty($user) || empty($pass) || empty($email))
{
	header("Location: index.php");
	die();
}

//Return to index on failed database connection. Maybe handle error?
if($manager->connectToDatabase() === FALSE)
{
	header("Location: index.php");
	die();
}


//Attempt to register user. If failure, return to index.
if($manager->registerNewUser($user, $pass, $email) === FALSE)
{
	header("Location: index.php");
	die();
}

//Success
else
{	
	//Send confirmation email to new user
	/**********************************************************
	 *  TEST AWS SES CAPABILITIES
	 *********************************************************/
	// Instantiates the ses client with AWS credentials
    include("template/aws.php");
    error_reporting(E_ALL);
    ini_set( 'display_errors', 'On');// Turn on debugging.
    $aws = new AWS();
    $SESClient = $aws->authSES();// Authorize the S3 object.
    //echo $SESClient;
    //$aws = Aws::factory('config.php');
    //$sesClient = $aws->get('ses');
    /*
    $client = SesClient::factory(array(
    'profile' => 'default',
    'region'  => 'us-west-2',
    ));*/
	//echo "Ses Client created.\n";
	/*
    Loops for each email address the user entered
    need to replace the email text data with an official link
    Link should be of the form: http://45.56.101.195/room.php?id=' . $id . '
    Need to integrate this code with create.php
    */
    
        //$toAddress = $_POST["emailAddress"][$i]
    	$result = $SESClient->sendEmail(array(
        // Source is required
        'Source' => 'harmony.mailservice@gmail.com',
        // Destination is required
        'Destination' => array(
            'ToAddresses' => array($email)
            //'CcAddresses' => array('string', ... ),
            //'BccAddresses' => array('string', ... ),
        ),
        // Message is required
        'Message' => array(
            // Subject is required
            'Subject' => array(
                // Data is required
                'Data' => 'Whats gucci! Its yo boi harmony'
                //'Charset' => 'string',
            ),
            // Body is required
            'Body' => array(
                'Text' => array(
                    // Data is required
                    'Data' => 'You just registered mah man! Holla back atchyo boi!'
                    //'Charset' => 'string',
                )/*
                'Html' => array(
                    // Data is required
                    'Data' => 'string',
                    'Charset' => 'string',
                ),*/
            ),
        ),/*
        'ReplyToAddresses' => array('string', ... ),
        'ReturnPath' => 'string',*/
    	));
    
	//echo "Email sent\n";

	$manager->logIn($user, $pass);
	die();
}

?>
