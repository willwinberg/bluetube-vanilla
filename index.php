<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");



$videoGrid = new VideoGrid($db, $user, true);
echo $videoGrid->render("Recommendations", NULL);
?>
<?php require_once("includes/footer.php"); ?>