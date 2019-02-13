<?php
require_once("../includes/config.php"); 
require_once("../includes/modelInterfaces/Comment.php"); 
require_once("../includes/modelInterfaces/User.php"); 

$username = $_SESSION["loggedIn"];
$videoId = $_POST["videoId"];
$commentId = $_POST["commentId"];
$action = $_POST["action"];

$user = new User($db, $username);
$comment = new Comment($db, $commentId, $user, $videoId);

if ($action === 'like') {
   echo $comment->addLike();
} else if ($action === 'dislike') {
   echo $comment->addDislike();
} elseif ($action === 'replies') {
   echo $comment->getRepliesArray();
} else {
   echo "Error in commentActions.php";
}
?>