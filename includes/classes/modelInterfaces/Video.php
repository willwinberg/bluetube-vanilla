<?php

class Video {

   private $dbConnection;
   private $user;

   public 
      $id,
      $tile,
      $description,
      $privacy,
      $category,
      $views,
      $duration,
      $filePath,
      $uploadedBy,
      $uploadedDate
   ;

   public function __construct($dbConnection, $input, $user) {
      $this->dbConnection = $dbConnection;
      $this->user = $user;

      if (is_array($input)) {
         $video = $input;
      } else {
         $query = $this->dbConnection->prepare(
            "SELECT * FROM videos WHERE id = :id"
         );
         $query->bindParam(":id", $input);
         $query->execute();

         $video = $query->fetch(PDO::FETCH_ASSOC);
      }

      $this->id = $video["id"];
      $this->title = $video["title"];
      $this->description = $video["description"];
      $this->privacy = $video["privacy"];
      $this->category = $video["category"];
      $this->views = $video["views"];
      $this->duration = $video["duration"];
      $this->filePath = $video["filePath"];
      $this->uploadedBy = $video["uploadedBy"];
      $this->uploadDate = $video["uploadDate"];  
   }

   public function generatePlayer($setAutoplay) {
      $autoplay = $setAutoplay ? "autoplay" : "";

      return ("
         <video class='videoPlayer' controls $autoplay>
            <source src='$this->filePath' type='video/mp4'>
                  Your browser does not support the video tag
         </video>
      ");
   }
   
   public function getUploadDate() {
      return date("M j, Y", strtotime($this->date));
   }

   public function getTimeStamp() {
      return date("M jS, Y", strtotime($this->date));
   }

   public function incrementViews() {
      $query = $this->dbConnection->prepare(
         "UPDATE videos SET views=views+1 WHERE id=:id"
      );
      $query->bindParam(":id", $this->id);
      $query->execute();

      $this->views++;
   }
   
}