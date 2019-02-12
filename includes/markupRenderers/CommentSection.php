<?php
class CommentSection {

   private $db, $video, $user;

   public function __construct($db, $video, $user) {
      $this->db = $db;
      $this->video = $video;
      $this->user = $user;
   }

   public function render() {
      $CommentCount = $this->video->getCommentCount();
      $videoId = $this->video->id;
      $username = $this->user->username;
      $profileButton = Button::profileButton($this->db, $username);
      $commentAction = "postComment(this, \"$username\", \"$videoId\", null, \"comments\")";
      $commentButton = Button::regular("COMMENT", $commentAction, "postComment", NULL);
      
      $comments = $this->video->getCommentsArray();
      $commentsMarkup = "";

      foreach ($comments as $comment) {
         // $comment = new CommentMarkup($this->db, $comment, $this->user, $videoId);

         $commentsMarkup .= $comment->render();
      }

      return "
         <div class='commentSection'>
            <div class='header'>
               <span class='commentCount'>$commentCount Comments</span>
               <div class='commentForm'>
                  $profileButton
                  <textarea class='commentTextarea' placeholder='Add a public comment'></textarea>
                  $commentButton
               </div>
            </div>
            <div class='comments'>
               $commentsMarkup
            </div>
         </div>
      ";
   }

}
?>