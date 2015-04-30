<?php
session_start();

include "MusicManger.php";
$manager = new MusicManager($_SESSION['User_id']);
$manager->connectToDatabase();

$edited = $manager->editSong($_POST['EditSongId'], $_POST['EditTitle'], $_POST['EditArtist']);

header('Location: manage.php?edited=' . $edited);
die();

?>
