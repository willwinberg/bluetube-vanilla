<?php 
require_once("includes/header.php"); 
require_once("includes/classes/modelInterfaces/Video.php"); 

if (!isset($_GET["id"])) {
    echo "Video URL missing";
    exit();
}

$video = new Video($dbConnection, $_GET["id"], $user);
$video->incrementViews();
echo $video->views;
?>

<div class="watchLeft">
   <?php echo $video->generatePlayer(true); ?>
</div>
<div class="suggestions">

</div>

<?php require_once("includes/footer.php"); ?>