<?php
require_once("../includes/config.php"); 
require_once("../includes/classes/modelInterfaces/Video.php"); 
require_once("../includes/classes/modelInterfaces/User.php"); 

$username = $_SESSION["loggedIn"];
$videoId = $_POST["videoId"];
$user = new User($dbConnection, $username);
$video = new Video($dbConnection, $videoId, $user);
echo $user->likeVideo($video);
?>
