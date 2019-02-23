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

$dataSanitizer = new FormInputSanitizer;

$data = $dataSanitizer->sanitize($_POST);

if (isset($_POST["uploadVideo"]) && isset($_FILES["file"])) {
    $data["video"] = $_FILES["file"];
    $data["username"] = $loggedInUsername;
    $videoProcessor = new VideoProcessor($db, $loggedInUsername);
    $id = $videoProcessor->uploadVideo($data);
    $noErrors = empty($videoProcessor->errors);
    var_dump($videoProcessor->errors);
    var_dump($_FILES);

    if ($id && $noErrors) {
        header("Location: editVideo.php?videoId=$id&success=true");
    } else {
        $message = Error::$upload;
    }
}

$form = new FormBuilder($data);
?>
<div class="row d-flex justify-content-center">
    <?php
    echo $form->openFormTag("Upload a video to BlueTube", "multipart/form-data");
        echo $message;
        if ($data["video"]) {
            echo "<ul>";
            foreach ($videoProcessor->errors as $error) echo "<li>" . $error . "</li>";
            echo "</ul>";
         }
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
                
