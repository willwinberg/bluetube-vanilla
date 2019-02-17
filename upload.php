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
    $uploadSuccess = $videoProcessor->uploadVideo($data);

    if ($uploadSuccess) {
        $message = "<div class='alert alert-success'>Video upload successful</div>";
    } else {
        $message = "<div class='alert alert-danger'>Video upload failed</div>";
    }
}
?>

<div class='column'>
<?php
$form = new FormBuilder;
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

<script>
$("form").submit(function() {
    $("#loadingModal").modal("show");
});
</script>

<?php require_once("includes/footer.php"); ?>
                
