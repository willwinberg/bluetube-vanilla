<?php
require_once("../includes/config.php");
require_once("../includes/modelInterfaces/User.php");
require_once("../includes/modelInterfaces/Comment.php");

if (isset($_POST['text']) && isset($_POST['username']) && isset($_POST['videoId'])) {
   $user = new User($db, $_SESSION["loggedIn"]);
   $postedBy = $_POST['postedBy'];
   $videoId = $_POST['videoId'];
   $replyTo = isset($_POST['replyTo']) ? $_POST['replyTo'] : 0;
   $body = $_POST['body'];

   $query = $db->prepare(
   "INSERT INTO comments (postedBy, videoId, replyTo, body)
    VALUES (:postedBy, :videoId, :replyTo, :body)"
   );
   $query->bindParam(":postedBy", $this->postedBy());
   $query->bindParam(":videoId", $this->videoId());
   $query->bindParam(":replyTo", $this->replyTo());
   $query->bindParam(":body", $this->body());

   $query->execute();

   $comment = $query->lastInsertId();
   $commentMarkup = new CommentMarkup($db, $comment, $user, $videoId);

   echo $commentMarkup->render();
} else {
      echo "One or more parameters are not passed into subscribe.php the file";
}
?>