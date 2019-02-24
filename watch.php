<?php 
require_once("includes/header.php"); 
require_once("includes/modelInterfaces/Video.php");
require_once("includes/modelInterfaces/Comment.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/VideoPlayer.php");
require_once("includes/markupRenderers/VideoInfo.php");
require_once("includes/markupRenderers/CommentSection.php");
require_once("includes/markupRenderers/CommentCard.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/VideoPlayer.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoInfo.css">
<link rel="stylesheet" type="text/css" href="assets/css/CommentSection.css">
<link rel="stylesheet" type="text/css" href="assets/css/CommentCard.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoGrid.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoCard.css">
<script src="assets/javascript/videoPlayerActions.js"></script>
<script src="assets/javascript/commentActions.js"></script>

<?php
if (!isset($_GET["id"])) {
    echo "Video URL missing";
    exit();
}

$video = new Video($db, $_GET["id"], $loggedInUser);
$video->incrementViews();

$videoPlayer = new VideoPlayer($video->filePath());
$videoInfo = new VideoInfo($db, $video, $loggedInUser);
$commentSection = new CommentSection($db, $video, $loggedInUser);

$cardFetcher = new VideoCardsFetcher($db, $loggedInUser);
$recommendedCards = $cardFetcher->getRecommended();
$recommendationsGrid = new VideoGrid($recommendedCards, "watchPage");
?>

<div class="row justify-center">
    <div class="col-lg-7">
    <?php
    echo $videoPlayer->render(true);
    echo $videoInfo->render();
    echo $commentSection->render();
    ?>
    </div>
    <div class="col-lg-5 d-none-lg pl-0">
        <?php echo $recommendationsGrid->render(""); ?>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>