<?php
require_once("../includes/config.php");
require_once("../includes/modelInterfaces/User.php");
require_once("../includes/modelInterfaces/Comment.php");
require_once("../includes/markupRenderers/CommentMarkup.php");

if (isset($_POST['body']) && isset($_POST['postedBy']) && isset($_POST['videoId'])) {
   $user = new User($db, $_SESSION["loggedIn"]);

   $postedBy = $_POST['postedBy'];
   $videoId = $_POST['videoId'];
   $replyTo = isset($_POST['replyTo']) ? $_POST['replyTo'] : 0;
   $body = $_POST['body'];

   $query = $db->prepare(
   "INSERT INTO comments (postedBy, videoId, replyTo, body)
    VALUES (:postedBy, :videoId, :replyTo, :body)"
   );
   $query->bindParam(":postedBy", $postedBy);
   $query->bindParam(":videoId", $videoId);
   $query->bindParam(":replyTo", $replyTo);
   $query->bindParam(":body", $body);

   $query->execute();

   $commentId = $db->lastInsertId();

   $commentMarkup = new CommentMarkup($db, 27, $user, $videoId);

   echo $commentMarkup->render();
} else {
   echo "postComment.php is missing parameters";
}
?>