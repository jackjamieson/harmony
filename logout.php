<?php
session_start(); //Opens the current session, if one exists.
session_unset(); //Frees all session variables.
session_destroy(); //Destroys the sessions.
header("Location: index.php"); //Redirect the user to the main screen.
die(); //Kill the script.
?>
