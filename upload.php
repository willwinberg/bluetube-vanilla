<?php
require_once("includes/header.php");
require_once("includes/markupRenderers/FormBuilder.php");
require_once("includes/markupRenderers/LoadingModal.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$form = new FormBuilder;
echo $form->openFormTag("processing.php", "multipart/form-data");
echo $form->FileInput("File");
echo $form->textInput("Title");
echo $form->textareaInput("Description");
echo $form->privacyInput();
echo $form->categoriesInput($db);
echo $form->submitButton("Upload", "uploadButton");
echo $form->closeFormTag();
?>

<script>
$("form").submit(function() {
    $("#loadingModal").modal("show");
});
</script>

<?php require_once("includes/footer.php"); ?>
                
