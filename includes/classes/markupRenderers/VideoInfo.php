<?php
class VideoInfo {

   private $dbConnection, $video, $user;

   public function __construct($dbConnection, $video, $user) {
      $this->dbConnection = $dbConnection;
      $this->title = $video->title;
      $this->views = $video->views;
      $this->user = $user;
   }

   public function renderInfo() {
      return "
         <div class='videoInfo'>
            <h1>$this->title</h1>
            <div class='bottomSection'>
               <span class='viewCount'>$this->views views</span>
            </div>
         </div>
      ";
   }

}
?>
