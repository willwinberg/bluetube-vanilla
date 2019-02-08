<?php 
require_once("includes/header.php"); 
require_once("includes/classes/modelInterfaces/Video.php");
require_once("includes/classes/markupRenderers/VideoInfo.php");

if (!isset($_GET["id"])) {
    echo "Video URL missing";
    exit();
}

$video = new Video($dbConnection, $_GET["id"], $user);
$video->incrementViews();

// $videoPlayer = new VideoPlayer($dbConnection, $video, $user);
$videoInfo = new VideoInfo($dbConnection, $video, $user);
?>

<div class="watchLeft">
   <?php echo $video->generatePlayer(true); ?>
   <?php echo $videoInfo->renderInfo(); ?>
</div>
<div class="suggestions">
</div>

<?php require_once("includes/footer.php"); ?>