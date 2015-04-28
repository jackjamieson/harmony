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
	
	$manager->logIn($user, $pass);
	die();
}

?>
