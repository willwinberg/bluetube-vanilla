<?php
require_once("includes/header.php");
require_once("includes/markupRenderers/FormProvider.php");
require_once("includes/markupRenderers/LoadingModal.php");

if (!User::isLoggedIn()) {
   header("Location: login.php");
}
?>
<script>
$("form").submit(function() {
    $("#loadingModal").modal("show");
});
</script>

<div class="column">
   <?php
    $uploadForm = new FormProvider($db, "uploadForm");
    echo $uploadForm->render();
    ?>
</div>

<?php require_once("includes/footer.php"); ?>
                
