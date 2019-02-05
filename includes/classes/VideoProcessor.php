<?php
class VideoProcessor {
   private $dB_Connection;
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

   public function __construct($dB_Connection) {
      $this->dB_Connection = $dB_Connection;
   }

   // TODO: deconstruct this
   public function upload($videoUploadData) {
      $targetDirectory = "uploads/videos/";
      $videoDataArray = $videoUploadData->getVideoDataArray();

      $tempFilePath = $targetDirectory . uniqid() . basename($videoDataArray["name"]);
      $tempFilePath = str_replace(" ", "_", $tempFilePath);

      $isValidVideoFile = $this->videoFileIsValid($videoDataArray, $tempFilePath);

      if (!$isValidVideoFile) {
         return false;
      }
      // TODO: deconstruct, if file moved successfully
      if (move_uploaded_file($videoDataArray["tmp_name"], $tempFilePath)) {
         $finalFilePath = $targetDirectory . uniqid() . ".mp4";

         if (!$this->insertVideoData($videoUploadData, $finalFilePath)) {
               echo "Insert query failed\n";
               return false;
         } return;

      

         return true;
      }
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