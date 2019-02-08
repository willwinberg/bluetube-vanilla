<?php
class VideoPlayer {

   private $filePath;

   public function __construct($filePath) {
      $this->filePath = $filePath;
   }

   public function render($setAutoplay) {
      $autoplay = $setAutoplay ? "autoplay" : "";

      return ("
         <video class='videoPlayer' controls $autoplay>
            <source src='$this->filePath' type='video/mp4'>
                  Your browser does not support the video tag
         </video>
      ");
    }

}
?>