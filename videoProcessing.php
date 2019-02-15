<?php 
require_once("includes/header.php");
require_once("includes/dataProcessors/VideoProcessor.php");

if (!isset($_POST["uploadButton"])) {
    echo "No file has been selected.";
    exit();
}

$uploadedVideoData = array(
   "video" => $_FILES["file"], 
   "title" => $_POST["title"],
   "description" => $_POST["description"],
   "privacy" => $_POST["privacy"],
   "category" => $_POST["category"],
   "username" => $user->username   
);

// $validatedVideoData = videoDataValidator($uploadedVideoData);

$videoProcessor = new VideoProcessor($db);

$uploadSuccessful = $videoProcessor->uploadVideo($uploadedVideoData); // TODO: pass $validatedVideoData

if ($uploadSuccessful) {
    echo "Video upload successful";
}

require_once("includes/footer.php");
?>
