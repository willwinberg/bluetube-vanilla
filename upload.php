<?php
require_once("includes/header.php");
require_once("includes/markupRenderers/FormBuilder.php");
require_once("includes/markupRenderers/LoadingModal.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}
?>

<div class='column'>
<?php
$form = new FormBuilder;
echo $form->openFormTag("processing.php", "multipart/form-data");
echo $form->FileInput("File", "file");
echo $form->textInput("Title", "title");
echo $form->textareaInput("Description", "description");
echo $form->privacyInput();
echo $form->categoriesInput($db);
echo $form->submitButton("Upload", "uploadButton");
echo $form->closeFormTag();
?>
</div>

<script>
$("form").submit(function() {
    $("#loadingModal").modal("show");
});
</script>

<?php require_once("includes/footer.php"); ?>
                
