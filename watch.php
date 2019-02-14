<?php 
require_once("includes/header.php"); 
require_once("includes/modelInterfaces/Comment.php");
require_once("includes/markupRenderers/VideoPlayer.php");
require_once("includes/markupRenderers/VideoInfo.php");
require_once("includes/markupRenderers/CommentSection.php");
require_once("includes/markupRenderers/CommentMarkup.php");
?>
<script src="assets/javascript/videoPlayerActions.js"></script>
<script src="assets/javascript/userActions.js"></script>
<script src="assets/javascript/commentActions.js"></script>

<?php
if (!isset($_GET["id"])) {
    echo "Video URL missing";
    exit();
}

// $user === new User from header
$video = new Video($db, $_GET["id"], $user);
$video->incrementViews();

$videoPlayer = new VideoPlayer($video->filePath);
$videoInfo = new VideoInfo($db, $video, $user);
$commentSection = new CommentSection($db, $video, $user);

$cardFetcher = new VideoCardsFetcher($db, $user);
$recommendedCards = $cardFetcher->getRecommended();
$videoGrid = new VideoGrid($recommendedCards);
?>

<div class="watchLeft">
   <?php echo $videoPlayer->render(true); ?>
   <?php echo $videoInfo->render(); ?>
   <?php echo $commentSection->render(); ?>
</div>
<div class="suggestions">
    <?php echo $videoGrid->render(); ?>
</div>

<?php require_once("includes/footer.php"); ?>