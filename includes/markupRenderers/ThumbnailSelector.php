<?php

class ThumbnailSelector {

   private $video;

   public function __construct($video) {
      $this->video = $video;
   }

   public function render() {
      $thumbnails = $this->video->getThumbnails();
      // var_dump($thumbnails);
      $html = "";

      foreach ($thumbnails as $thumbnail) {
         $html .= $this->makeThumbnail($thumbnail);
      }

      return "
         <div class='thumbnailsContainer'>
            <h3 class='header'>Select Display Thumbnail</h3>
            <div class='thumbnails'>
               $html
            </div>
         </div>
      ";
   }

   private function makeThumbnail($thumbnail) {
      $id = $thumbnail["id"];
      $videoId = $thumbnail["videoId"];
      $path = $thumbnail["filePath"];
      $selected = $thumbnail["selected"] === '1' ? "selected" : "";

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