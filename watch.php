<?php 
require_once("includes/header.php"); 
require_once("includes/modelInterfaces/Comment.php");
require_once("includes/markupRenderers/VideoPlayer.php");
require_once("includes/markupRenderers/VideoInfo.php");
require_once("includes/markupRenderers/CommentSection.php");
require_once("includes/markupRenderers/CommentCard.php");
?>
<script src="assets/javascript/videoPlayerActions.js"></script>
<script src="assets/javascript/commentActions.js"></script>

<?php
if (!isset($_GET["id"])) {
    echo "Video URL missing";
    exit();
}

$video = new Video($db, $_GET["id"], $user);
$video->incrementViews();

$videoPlayer = new VideoPlayer($video->filePath);
$videoInfo = new VideoInfo($db, $video, $user);
$commentSection = new CommentSection($db, $video, $user);

$cardFetcher = new VideoCardsFetcher($db, $user);
$recommendedCards = $cardFetcher->getRecommended();
$recommendationsGrid = new VideoGrid($recommendedCards);
?>

<div class="watchLeft">
   <?php
   echo $videoPlayer->render(true);
   echo $videoInfo->render();
   echo $commentSection->render();
   ?>
</div>
<div class="suggestions">
    <?php echo $recommendationsGrid->render(); ?>
</div>

<?php require_once("includes/footer.php"); ?>