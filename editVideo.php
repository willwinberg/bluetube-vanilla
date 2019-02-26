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
<link rel="stylesheet" type="text/css" href="assets/css/loadingModal.css">
<?php

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

if (!isset($_GET["videoId"])) {
    echo Error::$noVideoSelected;
    exit();
} else {
   $video = new Video($db, $_GET["videoId"], $loggedInUser);

   if ($video->uploadedBy() !== $loggedInUser->username()) {
      echo Error::$notOwnedVideo;
      exit();
   }
}
// if video just uploaded and browser rerouted here
if (isset($_GET["success"])) $message = Success::$upload;

$noChanges = isset($_POST["editVideo"])
   && sizeof(array_diff_assoc($_POST, $video->getDetailsArray())) === 1;

if ($noChanges && isset($_POST["editVideo"])) {
   $message = Error::$noChanges;

} else if (isset($_POST["editVideo"])) {
   $_POST["videoId"] = $_GET["videoId"];
   $data = FormInputSanitizer::sanitize($_POST);
   $account = new AccountHandler($db);

   $message = $account->updateVideo($data); 
}
?>

<div class='row'>
   <div class='col-md-8 row-sm'>
      <?php
      $form = new FormBuilder($video->getDetailsArray());     
      echo $form->openFormTag("Edit Video");
      echo $message;

         $videoPlayer = new VideoPlayer($video->filePath());
         echo $videoPlayer->render(false);
         
         echo $form->textInput("Title", "title");
         echo $form->textareaInput("Description", "description");
         echo $form->privacyInput();
         echo $form->categoriesInput($db);
      echo $form->submitButton("Submit", "editVideo");
      echo $form->closeFormTag();
      ?>
      <script>
         $("button").submit(function() {
            $("#deleteModal").modal("show");
         });
      </script>
   </div>
   <div class='col-md-4 row-sm'>
      <?php
      $thumbnails = new ThumbnailSelector($video);
      echo $thumbnails->render();
      ?>
   </div>
</div>

<?php require_once("includes/footer.php"); ?>
               
