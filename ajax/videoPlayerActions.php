<?php
require_once("../includes/config.php"); 
require_once("../includes/classes/modelInterfaces/Video.php"); 
require_once("../includes/classes/modelInterfaces/User.php"); 

$username = $_SESSION["loggedIn"];
$videoId = $_POST["videoId"];
$action = $_POST["action"];

$user = new User($dbConnection, $username);
$video = new Video($dbConnection, $videoId, $user);

if ($action == "like") {
   echo $user->likeVideo($video);
} else if ($action == "dislike") {
   echo $user->dislikeVideo($video);
}
?>
