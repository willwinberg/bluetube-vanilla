<?php

class VideoInfo {

   private $dbConnection, $video, $user;

   public function __construct($dbConnection, $video, $user) {
      $this->dbConnection = $dbConnection;
      $this->user = $user;

      $this->id = $id;
      $this->title = $video->title;
      $this->views = $video->views;
      $this->likes = $video->likes;

      $this->likeButton = $this->likeButton();
      $this->dislikeButton = $this->dislikeButton();
   }

   public function render() {
      return "
         <div class='videoInfo'>
            <h1>$this->title</h1>
            <div class='infoLower'>
               <span class='views'>$this->views views</span>
               <div class=likeButtons>
                  $this->likeButton
                  $this->dislikeButton
               </div>
            </div>
         </div>
      ";
   }

   private function likeButton() {
      // $action = "likeVideo(this, $this->id)";
      $src = "assets/images/icons/thumb-up.png";

      // if ($this->video->wasLiked()) {
      //    $src = "assets/images/icons/thumb-up-active.png";
      // }

      return "
      <button class='likeButton' onclick=''>
         <img src='$src' name='like' alt='like'/>
      </button>";
   }

   private function dislikeButton() {
      // $action = "dislikeVideo(this, $this->id)";
      $src = "assets/images/icons/thumb-down.png";

      // if ($this->video->wasDisliked()) {
      //    $src = "assets/images/icons/thumb-down-active.png";
      // }

      return "
      <button class='dislikeButton' onclick=''>
         <img src='$src' name='dislike' alt='dislike'/>
      </button>";
   }

}
?>
