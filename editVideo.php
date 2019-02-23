<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/markupRenderers/VideoPlayer.php");
require_once("includes/markupRenderers/ThumbnailSelector.php");
require_once("includes/markupRenderers/FormBuilder.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/VideoPlayer.css">
<link rel="stylesheet" type="text/css" href="assets/css/ThumbnailSelector.css">
<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
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

$noChanges = isset($_POST["editVideo"])
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

<div class='row'>
   <div class='col-8'>
      <?php
      $videoPlayer = new VideoPlayer($video->filePath());
      echo $videoPlayer->render(false);

      $form = new FormBuilder($video->getDetailsArray());     
      echo $form->openFormTag("Edit Video");
      echo $alert;
         echo $form->textInput("Title", "title");
         echo $form->textareaInput("Description", "description");
         echo $form->privacyInput();
         echo $form->categoriesInput($db);
      echo $form->submitButton("Submit", "editVideo");
      echo $form->closeFormTag();
      ?>
   </div>
   <div class='col-4'>
      <?php
      $thumbnails = new ThumbnailSelector($video);
      echo $thumbnails->render();
      ?>
   </div>
</div>

<?php require_once("includes/footer.php"); ?>
               
