<?php 
require_once("includes/header.php");
require_once("includes/modelInterfaces/UploadedVideoData.php");
require_once("includes/dataProcessors/VideoProcessor.php");

if (!isset($_POST["uploadButton"])) {
    echo "No file has been selected.";
    exit();
}

$uploadedVideoData = new UploadedVideoData(
   $_FILES["file"], 
   $_POST["title"],
   $_POST["description"],
   $_POST["privacy"],
   $_POST["category"],
   $user->username   
);

// $validatedVideoData = videoDataValidator($uploadedVideoData);

$videoProcessor = new VideoProcessor($db);

$uploadSuccessful = $videoProcessor->uploadVideo($uploadedVideoData); // TODO: pass $validatedVideoData

if ($uploadSuccessful) {
    echo "Video upload successful";
}

require_once("includes/footer.php");
?>
