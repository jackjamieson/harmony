<?php
session_start();

include "UserManager.php";

$user = $_POST['Username'];
$pass = $_POST['Password'];

$manager = new UserManager();

//Unset the POST value
unset($_POST['Password']);

if($manager->connectToDatabase() === FALSE)
{
	header("Location: index.php");
	die();
}

$manager->logIn($user, $pass);
?>
