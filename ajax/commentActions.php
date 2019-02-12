<?php
require_once("../includes/config.php"); 
require_once("../includes/classes/Comment.php"); 
require_once("../includes/classes/User.php"); 

$username = $_SESSION["loggedIn"];
$videoId = $_POST["videoId"];
$commentId = $_POST["commentId"];
$type = $_POST["action"];

$user = new User($db, $username);
$comment = new Comment($db, $commentId, $user, $videoId);

if ($type === 'like') {
   echo $comment->addLike();
} else if ($type === 'dislike') {
   echo $comment->addDislike();
} elseif ($type === 'replies') {
   echo $comment->getRepliesArray();
} else {
   echo "Error in commentActions.php";
}
?>