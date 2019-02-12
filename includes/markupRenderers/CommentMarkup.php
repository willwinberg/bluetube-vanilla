<?php
require_once("Button.php");

class CommentMarkup {

   private $db, $commentId, $user;

   public function __construct($db, $commentId, $user) {

      $this->comment = new Comment($db, $commentId, $user, $videoId);
      $this->db = $db;
      $this->user = $user;
   }

   public function render() {
      $id = $this->comment->id();
      $videoId = $this->comment->videoId();
      $body = $this->comment->body();
      $postedBy = $this->comment->postedBy();
      $likeCount = $this->comment->totalLikes();

      $profileButton = $this->profileButton();
      $replyButton = $this->replyButton();
      $likeButton = $this->likeButton($id, $videoId);
      $dislikeButton = $this->dislikeButton($id, $videoId);

      $timestamp = $this->timeElapsed();
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
                     <span class='timestamp'>$timestamp</span>
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
      $poster = $this->comment->postedBy();

      return Button::profileButton($this->db, $poster);
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
      $class = "dislikeButton";
      $src = "assets/images/icons/thumb-down.png";

      if (in_array($this->user->username, $usersWhoDisliked)) {
         $src = "assets/images/icons/thumb-down-active.png";
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

   private function timeElapsed($full = false) {
      $now = new DateTime;
      $ago = new DateTime($this->comment->postDate());
      $difference = $now->diff($ago);
      $difference->w = floor($difference->d / 7);
      $difference->d -= $difference->w * 7;

      $timespans = array(
         'y' => 'year',
         'm' => 'month',
         'w' => 'week',
         'd' => 'day',
         'h' => 'hour',
         'i' => 'minute',
         's' => 'second',
      );
      foreach ($timespans as $span => &$val) {
         if ($difference->$span) {
            $val = $difference->$span . ' ' . $val . ($difference->$span > 1 ? 's' : '');
         } else {
            unset($timespans[$span]);
         }
      }

      if (!$full) {
         $timespans = array_slice($timespans, 0, 1);
      }

      return $timespans ? implode(', ', $timespans) . ' ago' : 'just now';
   }

}
?>