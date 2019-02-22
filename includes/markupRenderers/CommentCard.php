<?php
require_once("Button.php"); 

class CommentCard extends Comment {

   public function render() {
      $body = $this->body();
      $postedBy = $this->postedBy();
      $likeCount = $this->likeCount();

      $profileButton = $this->profileButton();
      $replyButton = $this->replyButton();
      $likeButton = $this->likeButton();
      $dislikeButton = $this->dislikeButton();

      $timestamp = $this->timeElapsed();
      $replySection = $this->replySection();
      $repliesText = $this->getRepliesText();
      
      return "
         <div class='commentCard'>
            <div class='cardContent'>
               $profileButton
               <div>
                  <div class='commentHeader'>
                     <a href='channel.php?username=$postedBy'>
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
               $likeButton
               $likeCount
               $dislikeButton
               $replyButton
            </div>
               $replySection
               $repliesText
         </div>
      ";

   }

   private function replyButton() {
      $text = "REPLY";
      $action = "toggleReply(this)";

      return Button::regular($text, $action, "reply", null);
   }

   private function likeCount() {
      $text = $this->totalLikes();

      if ($text == 0) $text = "";
      return "<span class='likeCount'>$text</span>";
   }

   private function profileButton() {
      $poster = $this->postedBy();

      return Button::profileButton($this->db, $poster);
   }

   private function likeButton() {
      $id = $this->id();
      $videoId = $this->videoId();

      $usersWhoLiked = $this->usersWhoLikedArray();
      $action = "likeComment(this, $id, $videoId)";
      $class = "likeButton";
      $src = "assets/images/icons/thumb-up.png";

      if (in_array($this->user->username, $usersWhoLiked)) {
         $src = "assets/images/icons/thumb-up-active.png";
      }

      return Button::regular("", $action, $class, $src);
   }

   private function dislikeButton() {
      $id = $this->id();
      $videoId = $this->videoId();

      $usersWhoDisliked = $this->usersWhoDislikedArray();
      $action = "dislikeComment(this, $id, $videoId)";
      $class = "dislikeButton";
      $src = "assets/images/icons/thumb-down.png";

      if (in_array($this->user->username, $usersWhoDisliked)) {
         $src = "assets/images/icons/thumb-down-active.png";
      }

      return Button::regular("", $action, $class, $src);
   }

    private function replySection() {
      $id = $this->id();
      $videoId = $this->videoId();
      $username = $this->user->username;

      $profileButton = Button::profileButton($this->db, $username); 

      $cancelAction = "toggleReply(this)";
      $cancelButton = Button::regular("Cancel", $cancelAction, "cancelReply", NULL);
      $postAction = "postComment(this, \"$videoId\", \"$id\", \"repliesSection\"), toggleReply(this)";
      $postButton = Button::regular("Reply",$postAction, "postReply", NULL);

      return "
         <div class='commentForm hidden'>
            $profileButton
            <textarea class='commentBody' placeholder='Add a comment'></textarea>
            $cancelButton
            $postButton
         </div>
      ";
   }

   private function getRepliesText() {
      $id = $this->id();
      $videoId = $this->videoId();
      $replyCount = $this->getReplyCount();
      $plural = $replyCount > 1 ? "all $replyCount replies" : "reply";

      if ($replyCount > 0) {
         $repliesText = "
            <span
               class='repliesSection viewReplies'
               onclick='getReplies(this, $id, $videoId)'
            >
            View $plural
            </span>
         ";
      } else {
         $repliesText = "<div class='repliesSection'></div>";
      }

      return "
         $repliesText
         ";
   }

   private function timeElapsed($full = false) {
      $now = new DateTime;
      $ago = new DateTime($this->postDate());
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