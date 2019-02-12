<?php
class CommentMarkup {

  private $db, $comment, $user, $videoId;

   public function __construct($db, $comment, $user, $videoId) {
      $this->db = $db;
      $this->user = $user;
      $this->comment = $comment;
      $this->videoId = $videoId;
   }

   private function render() {
      $id = $this->comment->id();
      $videoId = $this->comment->videoId();
      $body = $this->comment->body();
      $postedBy = $this->comment->postedBy();
      $profileButton = $this->profileButton();
      $replyButton = $this->replyButton();
      $likeCount = $this->totalLikes();
      $likeButton = $this->likeButton($id, $videoId);
      $dislikeButton = $this->dislikeButton($id, $videoId);

      $replySection = $this->replySection($id, $videoId);
      $repliesText = $this->getRepliesText();
      
      return "
         <div class='itemContainer'>
            <div class='comment'>
               $profileButton
               <div class='mainContainer'>
                  <div class='commentHeader'>
                     <a href='profile.php?username=$postedBy'>
                        <span class='username'>$postedBy</span>
                     </a>
                     <span class='timestamp'>timestamp</span>
                  </div>
                  <div class='body'>
                     $body
                  </div>
               </div>
            </div>
            <div class='controls'>
               $replyButton
               $likesCount
               $likeButton
               $dislikeButton
            </div>
               $replySection
               $repliesText
         </div>
      ";

   }

   private function replyButton() {
      $text = "REPLY";
      $action = "toggleReplyButton(this)";

      return Button::regular($text, $action, null, null);
   }

   private function likesCount() {
      $totalLikes = $this->comment->totalLikes();

      if ($totalLikes === 0) $text = "";

      return "<span class='count'>$text</span>";
   }

   private function profileButton() {
      $poster = new User($this->db, $this->comment->postedBy());

      return Button::profileButton($poster->username, $poster->image);
   }

   private function likeButton($id, $videoId) {
      $usersWhoLiked = $this->comment->usersWhoLikedArray();
      $action = "likeComment(this, $id, $videoId)";
      $class = "likeButton";
      $src = "assets/images/icons/thumb-up.png";

      if (in_array($this->user->username, $usersWhoLiked)) {
         $src = "assets/images/icons/thumb-up-active.png";
      }

      return Button::regular("", $action, $class, $src);
   }

   private function dislikeButton($id, $videoId) {
      $usersWhoDisliked = $this->comment->usersWhoDislikedArray();
      $action = "dislikeComment(this, $id, $videoId)";
      $class = "likeButton";
      $src = "assets/images/icons/thumb-up.png";

      if (in_array($this->user->username, $usersWhoLiked)) {
         $src = "assets/images/icons/thumb-up-active.png";
      }

      return Button::regular("", $action, $class, $src);
   }

    private function replySection($id, $videoId) {
      $username = $this->user->username;
      $profileButton = Button::profileButton($this->db, $username); 

      $cancelButtonAction = "toggleReply(this)";
      $cancelButton = Button::regular("Cancel", $cancelButtonAction, "cancelComment", NULL);

      $postButtonAction = "postComment(this, $id, $videoId, $username, repliesSection)";
      $postButton = Button::regular("Reply",$postButtonAction, "postComment", NULL);

      return "
         <div class='commentInput hidden'>
            $profileButton
            <textarea class='commentBody' placeholder='Add a comment'></textarea>
            $cancelButton
            $postButton
         </div>
      ";
   }

   private function getRepliesText() {
      $replyCount = $this->comment->getReplyCount();

      if ($replyCount > 0) {
         $repliesText = "
            <span
               class='repliesSection viewReplies'
               onclick='getReplies(this, $id, $videoId)'
            >
            View all $replyCount replies
            </span>
         ";
      } else {
         $repliesText = "<div class='repliesSection'></div>";
      }

      return $repliesText;
   }

}
?>