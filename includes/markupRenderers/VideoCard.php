<?php
class VideoGridItem extends Video{

   private $expanded;

   public function __construct($expanded) {
      $this->largeMode = $largeMode;
   }

   public function render() {
      $thumbnail = $this->thumbnail();
      $details = $this->getDetails();

      $url = "watch.php?id=" . $this->id();

      return "
         <a href='$url'>
            <div class='videoCard'>
               $thumbnail
               $details
            </div>
         </a>
      ";
   }

   private function getThumbnail() {
      return "
         <div class='thumbnail'>
            <img src='$this->thumbnail'>
            <div class='duration'>
               <span>$this->duration</span>
            </div>
         </div>";

   }

   private function getDetails() {
      $timestamp = $this->getTimeStamp();
      $timestamp = $this->getDescription();

      return "<div class='details'>
                  <h3 class='title'>$this->title</h3>
                  <span class='username'>$this->uploadedBy</span>
                  <div class='child'>
                     <span class='views'>$this->views views - </span>
                     <span class='timestamp'>$timestamp</span>
                  </div>
                  $description
               </div>";
   }

   private function getDescription() {
      if ($this->expanded) {
         $description = $this->description;
         
         if (strlen($description) > 350) {
            $description = substr($description, 0, 347) . "...";
          } 

         return "
            <span class='description'>$description</span>";
      } else {

         return "";       
      }
   }

}
?>