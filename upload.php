<?php
require_once("includes/header.php");
require_once("includes/classes/VideoDetailsFormProvider.php");
?>

<div class="column">
   <?php
    $formProvider = new VideoDetailsFormProvider($dbConnection);
    echo $formProvider->createVideoUploadForm();
    ?>
</div>

<?php require_once("includes/footer.php"); ?>
                
