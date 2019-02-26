<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/VideoProcessor.php");
require_once("includes/markupRenderers/FormBuilder.php");
require_once("includes/markupRenderers/loadingModal.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<link rel="stylesheet" type="text/css" href="assets/css/loadingModal.css">
<?php
if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$videoProcessor = new VideoProcessor($db, $loggedInUsername);
$message = "";
$data = NULL;

if (isset($_POST["uploadVideo"]) && isset($_FILES["file"])) {
    $data = FormInputSanitizer::sanitize($_POST);
    $data["video"] = $_FILES["file"];
    $data["username"] = $loggedInUsername;

    $id = $videoProcessor->uploadVideo($data);
    $noErrors = empty($videoProcessor->errors);

    if ($id && $noErrors) {
        header("Location: editVideo.php?videoId=$id&success=true");
    } else {
        $message = ErrorMsg::$upload;
    }
}

$form = new FormBuilder($data);
?>
<div id='uploadForm' class='row-1'>
    <?php
    echo $form->openFormTag("Upload a video to BlueTube", "multipart/form-data");
        echo $message;
        echo $videoProcessor->errors();

        echo $form->FileInput("File", "file");
        echo $form->textInput("Title", "title");
        echo $form->textareaInput("Description", "description");
        echo $form->privacyInput();
        echo $form->categoriesInput($db);
        echo $form->submitButton("Upload", "uploadVideo");
    echo $form->closeFormTag();
    ?>
</div>

<script>
$("form").submit(function() {
    $("#loadingModal").modal("show");
});
</script>

<?php require_once("includes/footer.php"); ?>
                
