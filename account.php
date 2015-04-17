<?php
include "base.php"; //Handles session start and database interaction.

//If LoggedIn not set, return to home screen.
if(empty($_SESSION['LoggedIn']))
{
	header('Location: index.php');
	die();
}

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
            
         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Manage Account</h3>
          </div>
          <div class="panel-body">
            Change your account information here.
            <p></p>
              <!-- Table -->
              <table class="table" style="width:50%">
                <tbody>
                    <tr>
                        <td><b>Username: </b></td>
                        <td><input disabled="" type="text" class="form-control" value="<?php echo $_SESSION['Username'] ?>" aria-describedby="basic-addon1"></td>
                    </tr>
                    <tr>
                        <td><b>Email: </b></td>
                        <td><input type="text" class="form-control" value="User123@fake.com" aria-describedby="basic-addon1"></td>
                    </tr>
                    <tr>
                        <td><b>Current Password: </b></td>
                        <td><input type="password" class="form-control" value="" aria-describedby="basic-addon1"></td>
                    </tr>
                    <tr>
                        <td><b>New Password: </b></td>
                        <td><input type="password" class="form-control" value="" aria-describedby="basic-addon1"></td>
                    </tr>
                  </tbody>
              </table>
            
              
              <a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Update Account</a>
          </div>
        </div>
            
                

        </body>

</html>
