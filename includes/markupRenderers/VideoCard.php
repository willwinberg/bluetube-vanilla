<?php

class VideoCard extends Video {

   public function render() {
      $thumbnail = $this->makeThumbnail();
      $details = $this->makeDetails();
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

   public function setExpanded($bool) {
      $this->expanded = $bool;
   }

   private function makeThumbnail() {
      $thumbnail = $this->getThumbnails();
      $path = $thumbnail["filePath"];
      $id = $thumbnail["id"];
      $duration = $this->duration();

      return "
         <div class='thumbnail'>
            <img src='$path' alt='$id'>
            <div class='duration'>
               <span>$duration</span>
            </div>
         </div>
      ";
   }

   private function makeDetails() {
      $title = $this->title();
      $uploadedBy = $this->uploadedBy();
      $views = $this->views();
      $timestamp = $this->timestamp();
      $description = $this->makeDescription();

      return "
         <div class='details'>
            <h3 class='title'>$title</h3>
            <span class='uploadedBy'>$uploadedBy</span>
            <div class='lower'>
               <span class='views'>$views views - </span>
               <span class='timestamp'>$timestamp</span>
            </div>
            $description
         </div>
      ";
   }

   private function makeDescription() {
      if ($this->expanded) {
         $description = $this->description();
         
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