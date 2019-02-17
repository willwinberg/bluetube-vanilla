<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/markupRenderers/VideoPlayer.php");
require_once("includes/markupRenderers/ThumbnailSelector.php");
require_once("includes/markupRenderers/FormBuilder.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

if (!isset($_GET["videoId"])) {
    echo "<div class='alert alert-danger'>No video has been selected</div>";
    exit();
   } else {
      $video = new Video($db, $_GET["videoId"], $user);
      if ($video->uploadedBy !== $user->username) {
      echo "<div class='alert alert-danger'>You don't have permission to edit this video</div>";
      exit();
   }
}

$dataSanitizer = new FormInputSanitizer;
$account = new AccountHandler($db);

$data = $dataSanitizer->sanitize($_POST);

if (isset($_POST["editVideo"])) {
   $data["videoId"] = $_GET["videoId"];

   $editSuccess = $account->updateVideo($data);

   if ($editSuccess) {
      $message = "
         <div class='alert alert-success'>Video edit saved</div>
      ";
   } 
}
?>

<div class='videoEditContainer'>
   <div class='top'>
      <?php
      $videoPlayer = new VideoPlayer($video->filePath);
      echo $videoPlayer->render(false);

      $thumbnails = new ThumbnailSelector($video);
      echo $thumbnails->render();
      ?>
   </div>
   <?php
   $form = new FormBuilder((array)$video);
   echo $form->openFormTag("", "multipart/form-data");
      echo $message;
      echo $form->textInput("Title", "title");
      echo $form->textareaInput("Description", "description");
      echo $form->privacyInput();
      echo $form->categoriesInput($db);
      echo $form->submitButton("Submit", "editVideo");
   echo $form->closeFormTag();
   ?>
   </div>
</div>

<?php require_once("includes/footer.php"); ?>
               
