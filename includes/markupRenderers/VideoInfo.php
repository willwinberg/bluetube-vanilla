<?php
require_once("Button.php");

class VideoInfo {

   private $db, $video, $user;

   public function __construct($db, $video, $user) {
      $this->db = $db;
      $this->user = $user;
      $this->video = $video;
   }

   public function render() {
      $title = $this->video->title;
      $uploader = $this->video->uploadedBy;
      $views = $this->video->views;
      $uploadDate = $this->video->getUploadDate();
      $description = $this->video->description;
      $likeButton = $this->likeButton();
      $dislikeButton = $this->dislikeButton();
      $profileButton = $this->profileButton();
      $actionButton = $this->actionButton();

      return "
         <div class='upperInfo'>
            <h1>$title</h1>
            <div class='lower'>
               <span class='views'>$views views</span>
               <div class=likeButtons>
                  $likeButton
                  $dislikeButton
                  
               </div>
            </div>
         </div>
         <div class='lowerInfo'>
            <div class='top'>
               $profileButton
               <div class='uploadInfo'>
                  <span class='owner'>
                     <a href='profile.php?username=$uploader'>
                        $uploader
                     </a>
                  </span>
                  <span class='date'>Published on $uploadDate</span>
               </div>
                  $actionButton
            </div>
            <div class='description'>
               $description
            </div>
         </div>
      ";
   }

   private function profileButton() {
      $uploader = $this->getUploader();

      return Button::profileButton($uploader->username, $uploader->image);
   }

   private function actionButton() {
      if ($this->video->uploadedBy === $this->user->username) {
            $actionButton = Button::editVideoButton($this->video->id);
      } else {
         $uploader = $this->getUploader();

         $actionButton = Button::subscribeButton($uploader, $this->user);
      }

      return $actionButton;
   }

   private function likeButton() {
      $likedUsers = $this->video->getLikedUsernameArray();
      $text = sizeof($likedUsers);
      $videoId = $this->video->id;
      $action = "likeVideo(this, $videoId)";
      $class = "likeButton";
      $src = "assets/images/icons/thumb-up.png"; 

      if (in_array($this->user->username, $likedUsers)) {
         $src = "assets/images/icons/thumb-up-active.png";
      }

      return Button::regular($text, $action, $class, $src);
   }

   private function dislikeButton() {
      $dislikedUsers = $this->video->getDislikedUsernameArray();
      $text = sizeof($dislikedUsers);
      $videoId = $this->video->id;
      $action = "dislikeVideo(this, $videoId)";
      $class = "dislikeButton";
      $src = "assets/images/icons/thumb-down.png";

      if (in_array($this->user->username, $dislikedUsers)) {
         $src = "assets/images/icons/thumb-down-active.png";
      }     

      return Button::regular($text, $action, $class, $src);
   }

   private function getUploader() {
      return new User($this->db, $this->video->uploadedBy);
   }

}
?>
