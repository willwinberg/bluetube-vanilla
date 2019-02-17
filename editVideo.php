<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/VideoProcessor.php");
require_once("includes/markupRenderers/FormBuilder.php");
require_once("includes/markupRenderers/LoadingModal.php");

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

$data = $dataSanitizer->sanitize($_POST);

if (isset($_POST["editVideo"])) {
   // submit new details

   if ($editSuccess) {
      $message = "<div class='alert alert-success'>Video edit saved</div>";
   } 
}
?>

<div class='editVideoContainer'>
<?php
$form = new FormBuilder($_POST);
echo $form->openFormTag("upload.php", "multipart/form-data");
   echo $message;
   echo $form->FileInput("File", "file");
   echo $form->textInput("Title", "title");
   echo $form->textareaInput("Description", "description");
   echo $form->privacyInput();
   echo $form->categoriesInput($db);
   echo $form->submitButton("Upload", "uploadVideo");
echo $form->closeFormTag();
?>
</div>

<?php require_once("includes/footer.php"); ?>
               
