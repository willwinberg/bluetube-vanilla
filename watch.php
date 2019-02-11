<?php 
require_once("includes/header.php"); 
require_once("includes/classes/modelInterfaces/Video.php");
require_once("includes/classes/markupRenderers/VideoPlayer.php");
require_once("includes/classes/markupRenderers/VideoInfo.php");
?>
<script src="assets/javascript/videoPlayerActions.js"></script>
<script src="assets/javascript/userActions.js"></script>

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
?>

<div class="watchLeft">
   <?php echo $videoPlayer->render(true); ?>
   <?php echo $videoInfo->render(); ?>
</div>
<div class="suggestions">
</div>

<?php require_once("includes/footer.php"); ?>