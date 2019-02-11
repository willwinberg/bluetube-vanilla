<?php
require_once("../includes/config.php"); 
require_once("../includes/modelInterfaces/Video.php"); 
require_once("../includes/modelInterfaces/User.php"); 

$username = $_SESSION["loggedIn"];
$videoId = $_POST["videoId"];
$action = $_POST["action"];

$user = new User($db, $username);
$video = new Video($db, $videoId, $user);

if ($action === "like") {
   echo $video->addLike();
} else if ($action == "dislike") {
   echo $video->addDislike();
}
?>
