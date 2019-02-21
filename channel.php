<?php
require_once("includes/header.php");
require_once("includes/markupRenderers/ChannelView.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/ChannelView.css">
<?php

if (isset($_GET["username"])) {
   $channelUsername = $_GET["username"]; 
} else {
   echo "User profile not found, channel.php";
   exit();
}

$channelView = new ChannelView($db, $user, $channelUsername);

echo $channelView->render();

require_once("includes/footer.php");
?>