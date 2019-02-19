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

if (isset($_POST["editVideo"])) {
   $data = FormInputSanitizer::sanitize($_POST);
   $data["videoId"] = $_GET["videoId"];
   $account = new AccountHandler($db);

   $account->updateVideo($data);

   if ($account->success) {
      $message = $account->success;
   } else {
      $message = $account->error;
   }
}
?>
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

echo $form->openFormTag();
   echo $message;
   echo $form->textInput("Title", "title");
   echo $form->textareaInput("Description", "description");
   echo $form->privacyInput();
   echo $form->categoriesInput($db);
   echo $form->submitButton("Submit", "editVideo");
echo $form->closeFormTag();

require_once("includes/footer.php");
?>
               
