<?php
require_once("../includes/config.php");
require_once("../includes/modelInterfaces/User.php");
require_once("../includes/modelInterfaces/Comment.php");
require_once("../includes/dataProcessors/FormInputSanitizer.php");
require_once("../includes/markupRenderers/CommentCard.php");

if (isset($_POST['body']) && isset($_POST['videoId'])) {
   $user = new User($db, $_SESSION["loggedIn"]);
   $videoId = $_POST['videoId'];
   $replyTo = isset($_POST['replyTo']) ? $_POST['replyTo'] : 0;
   $body = FormInputSanitizer::sanitize($_POST['body']);

   $commentId = $user->postComment($videoId, $replyTo, $body);

   $commentCard = new CommentCard($db, $commentId, $user, $videoId);

   echo $commentCard->render();
} else {
   echo "postComment.php is missing parameters";
}
?>