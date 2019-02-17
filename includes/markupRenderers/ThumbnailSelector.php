<?php

class ThumbnailSelector {

   private $video;

   public function __construct($video) {
      $this->video = $video;
   }

   public function render() {
      $thumbnails = $this->video->getThumbnails();
      $html = "";

      foreach ($thumbnails as $thumbnail) {
         $html .= $this->makeThumbnail($thumbnail);
      }

      return "
         <div class='thumbnailsContainer'>
            $html
         </div>
      ";
   }

   private function makeThumbnail($thumbnail) {
      $id = $thumbnail["id"];
      $videoId = $thumbnail["videoId"];
      $path = $thumbnail["filePath"];
      $selected = $thumbnail["selected"] === 1 ? "selected" : "";

      return "
         <div
            class='thumbnail $selected'
            onclick='selectThumbnail(this, $id, $videoId)'
         >
            <img src='$path' alt='thumbnail$id' name='thumbnail$id'>
         </div>
      ";
   }
   
}
?>