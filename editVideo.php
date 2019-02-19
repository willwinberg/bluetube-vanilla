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
    $alert = Error::$noVideoSelected;
    exit();
} else {
   $video = new Video($db, $_GET["videoId"], $user);

   if ($video->uploadedBy !== $user->username) {
      $alert = Error::$notOwnedVideo;
      exit();
   }
}

$noChanges = isset($_POST) && $video->dataSameAs($_POST);

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
   $videoPlayer = new VideoPlayer($video->filePath);
   echo $videoPlayer->render(false);
   
   $thumbnails = new ThumbnailSelector($video);
   echo $thumbnails->render();
   ?>
</section>
<?php
$form = new FormBuilder((array)$video);
echo $form->openFormTag();
echo $alert;
echo $form->textInput("Title", "title");
echo $form->textareaInput("Description", "description");
echo $form->privacyInput();
echo $form->categoriesInput($db);
echo $form->submitButton("Submit", "editVideo");
echo $form->closeFormTag();

require_once("includes/footer.php");
?>
               
