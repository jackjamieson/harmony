<?php
//Starts session and makes sure user is logged in.
session_start();
include "UserManager.php";
UserManager::checkLogin();

//Connect to database to allow changes to email and password.
$manager = new UserManager();
$databaseConnected = $manager->connectToDatabase(); //Handle errors

//Variable for successful email and password changes.
$emailChanged = NULL;
$passwordChanged = NULL;

//Handle email update
if($databaseConnected === TRUE && !empty($_POST['newEmail']))
{
	//Check if password is valid. Change email if so.
	if($manager->checkPassword($_SESSION['User_id'], $_POST['currentPass']) === TRUE)
		$emailChanged = $manager->changeEmail($_SESSION['User_id'], $_POST['newEmail']);

	else
		$emailChanged = FALSE;
}

if($databaseConnected === TRUE && !empty($_POST['currentPass']) && !empty($_POST['newPass']))
{
	//First, check if current password is valid.
	//Then, change password if so.
	if($manager->checkPassword($_SESSION['User_id'], $_POST['currentPass']) === TRUE)
		$passwordChanged = $manager->changePassword($_SESSION['User_id'], $_POST['newPass']);
	else
		$passwordChanged = FALSE;
}


//Unset post variables. Make sure no errors.
if(isset($_POST['newEmail']))
	unset($_POST['newEmail']);
if(isset($_POST['currentPass']))
	unset($_POST['currentPass']);
if(isset($_POST['newPass']))
	unset($_POST['newPass']);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Harmony - Account Settings</title>
        <head>

            <!-- css imports !-->
            <?php include ('template/head.php'); ?>

        </head>

        <body>

				<script>
				function success(){
					document.getElementById("update_success").style.display="block";
				}

				function fail(){
					document.getElementById("update_fail").style.display="block";
				}
				</script>

            <!-- css/html nav !-->
            <!-- check for login before we draw the nav !-->
            <?php include ('template/nav.php') ?>

            <?php
                // Find out whether or not the user is logged in and pass it into Nav
                $nav = new Nav(true);
                $nav->render();
            ?>


            <?php

            error_reporting(E_ALL);


            ini_set( 'display_errors', 'On');// Turn on debugging.

            //include ('template/aws.php');// Include our aws services
            //include ('template/util.php');// Utility class for generating liquidsoap files
            ?>
						<div class="alert alert-success alert-dismissible" role="alert" id="update_success" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Account updated!</div>
						<div class="alert alert-warning alert-dismissible" role="alert" id="update_fail" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed to update account.</div>

         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Manage Account</h3>
          </div>
          <div class="panel-body">
            Change your account information here.
            <p></p>

		<form action="" method="post">
 	             <!-- Table -->
		      <table class="table" style="width:50%">
			<tbody>
			    <tr>
				<td><b>Username: </b></td>
				<td><input disabled="" type="text" class="form-control" value="<?php echo $_SESSION['Username'] ?>" aria-describedby="basic-addon1"></td>
			    </tr>
			    <tr>
				<td><b>Email: </b></td>
				<td><input type="text" name="newEmail" class="form-control" placeholder=<?php echo $_SESSION['EmailAddress'] ?> aria-describedby="basic-addon1"></td>
				<?php
				if($emailChanged === TRUE)
					echo '<td>Email updated!</td>';
				else if ($emailChanged === FALSE)
					echo '<td>Update failed</td>';
				?>
			    </tr>
			    <tr>
				<td><b>Current Password: </b></td>
				<td><input type="password" name="currentPass" class="form-control" value="" aria-describedby="basic-addon1"></td>

				<?php
				if($passwordChanged === TRUE)
					echo '<script>success();</script>';
				else if ($passwordChanged === FALSE){
					echo '<script>fail();</script>';
				}
				?>
			    </tr>
			    <tr>
				<td><b>New Password: </b></td>
				<td><input type="password" name="newPass" class="form-control" value="" aria-describedby="basic-addon1"></td>
			    </tr>
			  </tbody>
		      </table>

            <button type="submit" class="btn btn-primary">
              <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Update Account
            </button>

        	</form>

<!-- <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> -->
          </div>
        </div>





        </body>

</html>
