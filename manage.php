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


        <title>Harmony - Manage Music</title>
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
            <h3 class="panel-title">Manage Music</h3>
          </div>
          <div class="panel-body">
            You can edit and delete the songs you have previously uploaded here.
              <p></p>
              <div class="list-group" style="max-height:300px; overflow:auto;">
              <a href="#" class="list-group-item">Artist1 - Title</a>
              <a href="#" class="list-group-item">Artist2 - Title</a>
              <a href="#" class="list-group-item">Artist3 - Title</a>
              <a href="#" class="list-group-item">Artist4 - Title</a>
              <a href="#" class="list-group-item">Artist1 - Title</a>
              <a href="#" class="list-group-item">Artist2 - Title</a>
              <a href="#" class="list-group-item">Artist3 - Title</a>
              <a href="#" class="list-group-item">Artist4 - Title</a>
            </div>
              <a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
              <a href="#" class="btn btn-primary btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a>
          </div>
        </div>
            
         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Upload Music</h3>
          </div>
          <div class="panel-body">
            Upload songs here that you might want to use in a playlist.
            <p></p>
            <a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</a>
          </div>
        </div>
		
		<div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Upload + Transcode Music</h3>
          </div>
          <div class="panel-body">
            Upload non-mp3 songs here that you might want to use in a playlist. We will convert it to mp3 for you!
            <p></p>
            <a href="#" class="btn btn-primary btn-primary"><span class="glyphicon glyphicon-cloud-upload"></span> Upload + Transcode</a>
          </div>
        </div>
                

        </body>

</html>
