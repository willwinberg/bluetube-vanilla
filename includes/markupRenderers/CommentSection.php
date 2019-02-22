<link rel="stylesheet" type="text/css" href="assets/css/CommentSection.css">

<?php
class CommentSection {

   private $db, $video, $user;

   public function __construct($db, $video, $user) {
      $this->db = $db;
      $this->video = $video;
      $this->user = $user;
   }

   public function render() {
      $commentCount = $this->video->getCommentCount();
      $videoId = $this->video->id();
      $username = $this->user->username();
      $profileButton = Button::profileButton($this->db, $username);
      $commentAction = "postComment(this, \"$videoId\", null, \"comments\")";
      $commentButton = Button::regular("Submit", $commentAction, "postComment", NULL);
      
      $cards = $this->video->getCommentsArray();
      $commentsMarkup = "";

      foreach ($cards as $card) { 
         $commentsMarkup .= $card->render();
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