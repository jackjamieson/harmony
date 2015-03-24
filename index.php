<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Harmony</title>
        <head>

            <!-- css imports !-->
            <?php include ('template/head.php'); ?>

        </head>

        <body>

            <!-- css/html nav !-->
            <?php include ('template/nav.php') ?>
            
            <?php 
                // Find out whether or not the user is logged in and pass it into Nav
                $nav = new Nav(false); 
                $nav->render();
            ?>



            <div class="jumbotron">
                <h1>Listen to music.  Together.</h1>
            </div>

        </body>

        </html>
