<?php
class VideoProcessor {
   private $dbConnection;
   private $sizeLimit = 500000000; // 500mb
   private $allowedTypes = array(
      "avi",
      "flv",
      "mkv",
      "mp4",
      "mpeg",
      "mpg",
      "mov",
      "ogg",
      "ogv",
      "vob",
      "webm",
      "wmv",
   );

   public function __construct($dbConnection) {
      $this->dbConnection = $dbConnection;
   }

   // TODO: deconstruct this
   public function upload($cleanVideoData) {
      $targetDirectory = "uploads/videos/";
      $videoDataArray = $cleanVideoData->getVideoDataArray();

      $tempFilePath = $targetDirectory . uniqid() . basename($videoDataArray["name"]);
      $tempFilePath = str_replace(" ", "_", $tempFilePath);

      $isValidVideoFile = $this->videoFileIsValid($videoDataArray, $tempFilePath);

      if (!$isValidVideoFile) {
         return false;
      }
      // TODO: deconstruct, if file moved successfully
      if (move_uploaded_file($videoDataArray["tmp_name"], $tempFilePath)) {
         $finalFilePath = $targetDirectory . uniqid() . ".mp4";

         if (!$this->insertVideoData($cleanVideoData, $finalFilePath)) {
               echo "Insert query failed\n";
               return false;
         } 

         return true;
      }
   }
   
   private function insertVideoData($cleanVideoData, $filePath) {
      $query = $this->dbConnection->prepare(
         "INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath)
         VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)"
      );

      $title = $cleanVideoData->getTitle();
      $uploadedBy = $cleanVideoData->getUploadedBy();
      $description = $cleanVideoData->getDescription();
      $privacy = $cleanVideoData->getPrivacy();
      $category = $cleanVideoData->getCategory();

      $query->bindParam(":title", $title);
      $query->bindParam(":uploadedBy", $uploadedBy);
      $query->bindParam(":description", $description);
      $query->bindParam(":privacy", $privacy);
      $query->bindParam(":category", $category);

      $query->bindParam(":filePath", $filePath);

      return $query->execute();
    }

   private function videoFileIsValid($videoData, $filePath) {
      $videoType = pathInfo($filePath, PATHINFO_EXTENSION);
      
      if (!$this->isValidSize($videoData)) {
         echo "File must be no larger than " . $this->sizeLimit /1000000. . " megabytes.";
         return false;
      } else if (!$this->isValidType($videoType)) {
         echo "Invalid file type. Supported file types include";
         return false;
      } else if ($this->hasError($videoData)) {
         echo "Error: " . $videoData["error"];
         return false;
      }

      return true;
   }

   private function isValidSize($videoData) {
        return $videoData["size"] <= $this->sizeLimit;
    }

    private function isValidType($videoType) {
        $lowercased = strtolower($videoType);
        return in_array($lowercased, $this->allowedTypes);
    }
    
    private function hasError($videoData) {
        return $videoData["error"] != 0;
    }
}
?>