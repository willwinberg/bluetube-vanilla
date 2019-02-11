<?php 
require_once("includes/header.php");
require_once("includes/classes/modelInterfaces/UploadedVideoData.php");
require_once("includes/classes/dataProcessors/VideoProcessor.php");

if (!isset($_POST["uploadButton"])) {
    echo "No file has been selected.";
    exit();
}

$uploadedVideoData = new UploadedVideoData(
   $_FILES["fileInput"], 
   $_POST["titleInput"],
   $_POST["descriptionInput"],
   $_POST["privacyInput"],
   $_POST["categoryInput"],
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
