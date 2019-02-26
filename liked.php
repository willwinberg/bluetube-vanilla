
<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/VideoGrid.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoCard.css">
<?php

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$cardFetcher = new VideoCardsFetcher($db, $loggedInUser);
$likedCards = $cardFetcher->getLiked();

$likedGrid = new VideoGrid($likedCards, "liked");
$html = $likedGrid->render("Videos You Liked");
if ($html) {
   echo $html;
} else {
   echo ErrorMsg::$noLiked;
}

require_once("includes/footer.php"); 
?>