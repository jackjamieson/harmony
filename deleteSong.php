<?php
session_start();

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include "MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$manager->connectToDatabase();

$result = $manager->deleteSong($_POST['DeleteId']);

header('Location: manage.php?deleted=' . $result);
die();

?>
