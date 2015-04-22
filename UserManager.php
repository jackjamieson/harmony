<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class UserManager {

	private $database;//, $user_id;

	function __construct()
	{
//		$this->user_id = $user_id;
	}
	
	public function connectToDatabase()
	{
		
		$servername = "tcnj-csc470-preussr1-mysql1.cmlmk2o0jbuf.us-east-1.rds.amazonaws.com:3306";
		$username = "preussr1";
		$password = "mypassword";
		$dbname = "MySql1";

		//Create connection
		$this->database = new mysqli($servername, $username, $password, $dbname);

		//Check connection
		if($this->database->connect_error)
			return FALSE;
		else
			return TRUE;
	}

	//Change the email if possible.
	public function changeEmail($userId, $newEmail)
	{
		//If email not available, return FALSE.
		if(self::emailAvailable($newEmail) === FALSE)
			return FALSE;

		//Email available, make the change.
		$updateQuery = $this->database->prepare("UPDATE user SET email=? WHERE user_id=?");
		$updateQuery->bind_param("si", $newEmail, $userId);

		if($updateQuery->execute() === TRUE)
			return TRUE;
		else
			return FALSE;
	}

	
	//Change the password.
	public function changePassword($userId, $newPassword)
	{
		//Hash the password.
		$hash = password_hash($newPassword, PASSWORD_DEFAULT);

		//Submit the query.
		$updateQuery = $this->database->prepare("UPDATE user SET password=? WHERE user_id=?");
		$updateQuery->bind_param("si", $hash, $userId);

		if($updateQuery->execute() === TRUE)
			return TRUE;
		else
			return FALSE;
	}

	//Returns TRUE if email available. FALSE if not or if errors.
	private function emailAvailable($email) {

		//Prepares query and executes it.
		$checkEmailQuery = $this->database->prepare("SELECT user_id FROM user WHERE email=?");	
		$checkEmailQuery->bind_param("s", $email);
		$checkEmailQuery->execute();
		$checkEmailResult = $checkEmailQuery->get_result();
		
		//Error, return false.
		if($checkEmailResult === FALSE)
			return FALSE;

		//User already exists with that email address
		if($checkEmailResult->num_rows === 1)
			return FALSE;

		//Email available
		else
			return TRUE;		
	}

	
	//Registers a new user in the database.
	//Returns TRUE if successful, FALSE otherwise.
	function registerNewUser($user, $pass, $email)
	{	
		//Check if username is available.
		if($this->usernameAvailable($user) === FALSE)
			return FALSE;

		//Check if email is available
		if($this->emailAvailable($email) === FALSE)
			return FALSE;

		//Hash password	
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		
		$registerQuery = $this->database->prepare("INSERT INTO user (username, password, email) VALUES (?, ?, ?)");
		$registerQuery->bind_param("sss", $user, $hash, $email);
		
		//Success
		if($registerQuery->execute())
			return TRUE;
		
		//Error registering user
		else
			return FALSE;
	}

	//Returns TRUE if username available. FALSE if not or if errors.
	private function usernameAvailable($user) {
		
		//Prepares query and executes it.
		$checkUserQuery = $this->database->prepare("SELECT user_id FROM user WHERE username=?");	
		$checkUserQuery->bind_param("s", $user);
		$checkUserQuery->execute();
		$checkUserResult = $checkUserQuery->get_result();

		if($checkUserResult === FALSE)
			return FALSE;

		// Query successful
		//User already exists with that name
		if($checkUserResult->num_rows === 1)
			return FALSE;

		//Username available
		else
			return TRUE;
		
	}

	public function logIn($user, $pass)
	{
				
		//If values are empty, return false.
		if(empty($user) && empty($pass))
		{
			return FALSE;
		}

		//Make sure username is valid
		if($this->usernameAvailable($user) === TRUE)
		{
			return FALSE;
		}
	

		//First retrieves the hashed password from the database.
		$passQuery = $this->database->prepare("SELECT user_id, password, email FROM user WHERE username=?");

		$passQuery->bind_param("s", $user);
		$passQuery->execute();
		$result= $passQuery->get_result();

		$row = $result->fetch_row();
		$hash = $row[1];

		if(password_verify($pass, $hash))
		{	
			$_SESSION['Username'] = $user;
			$_SESSION['User_id'] = $row[0];
			$_SESSION['EmailAddress'] = $row[2];
			$_SESSION['LoggedIn'] = 1;
			
			header('Location: manage.php');
			die();
		}
		else
		{

			//echo 'PROBLEM';
			header('Location: index.php');
			die();
		}

	}

	//Checks to see if a user is logged in to the session.
	//If not, redirects to homepage.
	public static function checkLogin()
	{
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
	}

}

?>
