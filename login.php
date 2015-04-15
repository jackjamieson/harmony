<?php
include "base.php";

//Checking login on form submit
if(!empty($_POST['uLogin']) && !empty($_POST['uPassword']))
{	
	//First retrieves the hashed password from the database.
	$passQuery = $conn->prepare("SELECT user_id, password, email FROM user WHERE username=?");

	$passQuery->bind_param("s", $_POST['uLogin']);
	$passQuery->execute();
	$result= $passQuery->get_result();

	$row = $result->fetch_row();
	$hash = $row[1];

	if(password_verify($_POST['uPassword'], $hash))
	{	
		$_SESSION['Username'] = $_POST['uLogin'];
		$_SESSION['User_id'] = $row[0];
		$_SESSION['EmailAddress'] = $row[2];
		$_SESSION['LoggedIn'] = 1;
		
		unset($_POST['uPassword']);
		header('Location: manage.php');
		die();
	}	
}

header('Location: index.php');
die();
?>
