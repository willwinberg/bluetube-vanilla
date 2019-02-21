<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/markupRenderers/VideoPlayer.php");
require_once("includes/markupRenderers/ThumbnailSelector.php");
require_once("includes/markupRenderers/FormBuilder.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/VideoPlayer.css">
<link rel="stylesheet" type="text/css" href="assets/css/ThumbnailSelector.css">
<script src="assets/javascript/selectThumbnail.js"></script>
<script src="assets/javascript/selectFormBuilder.js"></script>
<?php

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

if (!isset($_GET["videoId"])) {
    echo Error::$noVideoSelected;
    exit();
} else {
   $video = new Video($db, $_GET["videoId"], $user);

   if ($video->uploadedBy() !== $user->username()) {
      echo Error::$notOwnedVideo;
      exit();
   }
}
// if video just uploaded and browser rerouted here
if (isset($_GET["success"])) $alert = Success::$upload;

$noChanges = isset($_POST)
   && !array_intersect($_POST, $video->getDetailsArray());

if ($noChanges) {
   $alert = Error::$noChanges;

} else if (isset($_POST["editVideo"])) {
   $data = FormInputSanitizer::sanitize($_POST);
   $data["videoId"] = $_GET["videoId"];

   $account = new AccountHandler($db);

   $alert = $account->updateVideo($data);
}
?>

<section class='row'>
   <?php
   $videoPlayer = new VideoPlayer($video->filePath());
   echo $videoPlayer->render(false);
   
   $thumbnails = new ThumbnailSelector($video);
   echo $thumbnails->render();
   ?>
</section>
<?php
$form = new FormBuilder($video->getDetailsArray());

echo $alert;
echo $form->openFormTag("Edit Video");
   echo $form->textInput("Title", "title");
   echo $form->textareaInput("Description", "description");
   echo $form->privacyInput();
   echo $form->categoriesInput($db);
echo $form->submitButton("Submit Changes", "editVideo");
echo $form->closeFormTag();

require_once("includes/footer.php");
?>
               
