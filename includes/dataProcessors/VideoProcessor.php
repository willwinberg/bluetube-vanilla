<?php
class VideoProcessor {
   private $db;
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

   private $ffmpegPath = "assets/ffmpeg/ffmpeg";
   private $ffprobePath = "assets/ffmpeg/ffprobe";

   public $id = null;
   public $errors = array();

   public function __construct($db) {
      $this->db = $db;
   }

   // TODO: deconstruct this
   public function uploadVideo($data) {
      $targetDirectory = "uploads/videos/";
      $videoDataArray = $data["video"];

      $tempFilePath = $targetDirectory . uniqid() . basename($videoDataArray["name"]);
      $tempFilePath = str_replace(" ", "_", $tempFilePath);
      echo "videoFileIsValid() pre";
      $isValidVideoFile = $this->videoFileIsValid($videoDataArray, $tempFilePath);
      echo "videoFileIsValid() post";

      if (!$isValidVideoFile) {
         return false;
      }

      if (move_uploaded_file($videoDataArray["tmp_name"], $tempFilePath)) {
         $finalFilePath = $targetDirectory . uniqid() . ".mp4";

         if (!$this->insertVideoIntoDB($data, $finalFilePath)) {
            $errors[] = "Insert query failed\n";
            return false;
         } 

         if (!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
            $errors[] = "Converting video to mp4 failed\n";
            return false;
         }  

         if (!$this->deleteTempMovieFile($tempFilePath)) {
            $errors[] = "Tempfile deletion failed\n";
            return false;
         }

         if (!$this->generateVideoThumbnails($finalFilePath)) {
            $errors[] = "Generating thumbnails failed\n";
            return false;
         }

         return $this->id;
      }
   }
   
   private function insertVideoIntoDB($data, $filePath) {
      $query = $this->db->prepare(
         "INSERT INTO videos (title, uploadedBy, description, privacy, category, filePath)
         VALUES (:title, :uploadedBy, :description, :privacy, :category, :filePath)"
      );

      $query->bindParam(":title", $data["title"]);
      $query->bindParam(":description", $data["description"]);
      $query->bindParam(":privacy", $data["privacy"]);
      $query->bindParam(":category", $data["category"]);
      $query->bindParam(":uploadedBy", $data["username"]);
      $query->bindParam(":filePath", $filePath);

      $success = $query->execute();
      $this->id = $this->db->lastInsertId();
      return $success;
    }

   public function convertVideoToMp4($tempFilePath, $finalFilePath) {
      $bashCommand = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";
      $outputLog = array();

      exec($bashCommand, $outputLog, $returnCode);
      
      if ($returnCode !== 0) { 
         foreach($outputLog as $line) {
            echo $line . "<br>";
         }

         return false;
      }

      return true;
   }

   private function deleteTempMovieFile($tempFilePath) {
      if (!unlink($tempFilePath)) {
         $errors[] = "Could not delete file\n";
         return false;
      }

      return true;
   }

   public function generateVideoThumbnails($finalFilePath) {
      $thumbnailSize = "210x118";
      $thumbnailCount = 3;
      $thumbnailPath = "uploads/videos/thumbnails";
      
      $duration = $this->getVideoDuration($finalFilePath);

      $videoId = $this->db->lastInsertId();
      $this->updateVideoDurationInDB($duration, $videoId);

      for ($num = 1; $num <= $thumbnailCount; $num++) {
         $imageName = uniqid() . ".jpg";
         $interval = ($duration * 0.8) / ($thumbnailCount * $num);
         $finalThumbnailPath = "$thumbnailPath/$videoId-$imageName";

         $bashCommand = "$this->ffmpegPath -i $finalFilePath -ss $interval -s $thumbnailSize -vframes 1 $finalThumbnailPath 2>&1";

         $outputLog = array();
         exec($bashCommand, $outputLog, $returnCode);
         
         if ($returnCode != 0) { // if command failed
            foreach($outputLog as $line) {
               echo $line . "<br>";
            }
         }

         $query = $this->db->prepare(
            "INSERT INTO thumbnails (videoId, filePath, selected)
            VALUES (:videoId, :filePath, :selected)"
         );

         $query->bindParam(":videoId", $videoId);
         $query->bindParam(":filePath", $finalThumbnailPath);
         $query->bindParam(":selected", $selected);

         $selected = $num == 1 ? 1 : 0;

         $success = $query->execute();

         if (!$success) {
            $errors[] = "Error inserting thumbnails into database\n";
            return false;
         }
      }

      return true;
   }

   private function getVideoDuration($finalFilePath) {
      return (int)shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $finalFilePath");
   }

   private function updateVideoDurationInDB($duration, $videoId) {   
      $durationString = $this->convertDurationToString($duration);

      $query = $this->db->prepare("UPDATE videos SET duration=:durationString WHERE id=:videoId");

      $query->bindParam(":durationString", $durationString);
      $query->bindParam(":videoId", $videoId);

      $query->execute();
   }

   private function convertDurationToString($duration) {
      $hours = floor($duration / 3600);
      $mins = floor(($duration - ($hours*3600)) / 60);
      $secs = floor($duration % 60);
      
      $hoursString = ($hours < 1) ? "" : $hours . ":";
      $minsString = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
      $secsString = ($secs < 10) ? "0" . $secs : $secs . "";

      $durationString = $hoursString . $minsString . $secsString;

      return $durationString;
   }

   private function videoFileIsValid($videoData, $filePath) {
      $videoType = pathInfo($filePath, PATHINFO_EXTENSION);
      echo "videoFileIsValid()";
      if (!$this->isValidSize($videoData)) {
         $errors[] = "File must be no larger than " . $this->sizeLimit /1000000. . " megabytes.";
         return false;
      } else if (!$this->isValidType($videoType)) {
         $errors[] = "Invalid file type. Supported file types include" . implode(", ", $this->allowedTypes);
         return false;
      } else if ($this->hasError($videoData)) {
         $errors[] = "Error: " . $videoData["error"];
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