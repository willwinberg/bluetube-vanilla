<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/ChannelView.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/ChannelView.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoGrid.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoCard.css">
<?php

if (isset($_GET["username"])) {
   $channelUsername = $_GET["username"]; 
} else {
   echo "User profile not found, channel.php";
   exit();
}

$channelView = new ChannelView($db, $loggedInUser, $channelUsername);

echo $channelView->render();

require_once("includes/footer.php");
?>