<?php

//Starts session which saves login information.
session_start();

//If logged in and not at index.php, redirect back to index.php.
if(empty($_SESSION['LoggedIn']))
{
	$currentPage = basename($_SERVER['PHP_SELF']);
	 
	//If not at home page and not logging in, redirect.
	if($currentPage != 'index.php' && $currentPage != 'login.php')
	{
		header('Location: index.php');
		die();
	}
}

//Database connection information.
$servername = "tcnj-csc470-preussr1-mysql1.cmlmk2o0jbuf.us-east-1.rds.amazonaws.com:3306";
$username = "preussr1";
$password = "mypassword";
$dbname = "MySql1";

//Establishes connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Checks connection
if($conn->connect_error)
	die("Connection failed: " . $conn->connect_error);
?>
