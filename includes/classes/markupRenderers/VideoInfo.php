<?php
require_once("Button.php");

class VideoInfo {

   private $dbConnection, $video, $user;

   public function __construct($dbConnection, $video, $user) {
      $this->dbConnection = $dbConnection;
      $this->user = $user;

      $this->video = $video;
      $this->id = $video->id;
      $this->title = $video->title;
      $this->description = $video->description;
      $this->uploadDate = $video->getUploadDate();
      $this->uploadedBy = $video->UploadedBy;
      $this->views = $video->views;
   }

   public function render() {
      $likeButton = $this->likeButton();
      $dislikeButton = $this->dislikeButton();
      $profileButton = $this->profileButton();
      $actionButton = $this->actionButton();

      return "
         <div class='videoInfo'>
            <h1>$this->title</h1>
            <div class='infoLower'>
               <span class='views'>$this->views views</span>
               <div class=likeButtons>
                  $likeButton
                  $dislikeButton
                  
               </div>
            </div>
         </div>
         <div class='secondaryInfo'>
            <div class='topRow'>
               $profileButton
               <div class='uploadInfo'>
                  <span class='owner'>
                     <a href='profile.php?username=$this->uploadedBy'>
                        $this->uploadedBy
                     </a>
                  </span>
                  <span class='date'>Published on $this->uploadDate</span>
               </div>
                  $actionButton
            </div>
            <div class='descriptionContainer'>
               $this->description
            </div>
         </div>
      ";
   }

   private function profileButton() {
      $uploader = $this->getUploader();

      return Button::profileButton($uploader->username, $uploader->image);
   }

   private function actionButton() {
      if ($this->uploadedBy === $this->user->username) {
            $actionButton = Button::editVideoButton($this->id);
      } else {
         $uploader = $this.getUploader();

         $actionButton = Button::subscribeButton($this->dbConnection, $uploader, $this->user);
      }

      return $actionButton;
   }

   private function likeButton() {
      $likedUsers = $this->video->getLikedUsernameArray();
      $text = sizeof($likedUsers);
      $action = "likeVideo(this, $this->id)";
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
      $action = "dislikeVideo(this, $this->id)";
      $class = "dislikeButton";
      $src = "assets/images/icons/thumb-down.png";

      if (in_array($this->user->username, $dislikedUsers)) {
         $src = "assets/images/icons/thumb-down-active.png";
      }
      

      return Button::regular($text, $action, $class, $src);
   }

   private function getUploader() {
      return new User($this->dbConnection, $this->uploadedBy);
   }

}
?>
