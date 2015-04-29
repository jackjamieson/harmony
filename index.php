<?php
session_start();


if(empty($_SESSION['LoggedIn']))
	$loggedIn = FALSE;
else
	$loggedIn = TRUE;
?>

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
                $nav = new Nav($loggedIn);
                $nav->render();
            ?>

			<div class="alert alert-warning alert-dismissible" role="alert" id="login_fail" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Login failed.</div>
			<div class="alert alert-warning alert-dismissible" role="alert" id="login_null" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>You must must be logged in to perform that action.</div>
				

			<?php
				// if the url has an id query then use that to print appropriate banner
				if(isset($_GET["id"])){
					$code = $_GET["id"];
					
					if ($code === "fail") {
						?>
						<script>
							document.getElementById("login_fail").style.display="block";
						</script>
						<?php
					}
					if ($code === "null") {
						?>
						<script>
							document.getElementById("login_null").style.display="block";
						</script>
						<?php
					}
					else {
						
					}
				}
			?>

            <div class="jumbotron">
                <h1>Listen to music.  Together.</h1>

                <p>Harmony is a real-time collaborative playlist creation tool.  Share and listen to music with your friends as if
                you were all in the same room.</p>

                <p><a href="create.php" class="btn btn-primary btn-lg" role="button">
                   Get Started</a>
                </p>
            </div>

        </body>

        </html>
