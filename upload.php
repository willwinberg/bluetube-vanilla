<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/VideoProcessor.php");
require_once("includes/markupRenderers/FormBuilder.php");
require_once("includes/markupRenderers/LoadingModal.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$dataSanitizer = new FormInputSanitizer;

$data = $dataSanitizer->sanitize($_POST);

if (isset($_POST["uploadVideo"]) && isset($_FILES["file"])) {
    $data["video"] = $_FILES["file"];
    $data["username"] = $loggedInUsername;
    $videoProcessor = new VideoProcessor($db, $loggedInUsername);
    $id = $videoProcessor->uploadVideo($data);

    if ($id) {
        header("Location: editVideo.php?videoId=$id&success=true");
    } else {
        $message = Error::$upload;
    }
}

$form = new FormBuilder($data);

echo $form->openFormTag("multipart/form-data");
    echo $message;
    echo $form->FileInput("File", "file");
    echo $form->textInput("Title", "title");
    echo $form->textareaInput("Description", "description");
    echo $form->privacyInput();
    echo $form->categoriesInput($db);
    echo $form->submitButton("Upload", "uploadVideo");
echo $form->closeFormTag();
?>

<script>
$("form").submit(function() {
    $("#loadingModal").modal("show");
});
</script>

<?php require_once("includes/footer.php"); ?>
                
