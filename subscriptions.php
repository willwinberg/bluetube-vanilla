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
$subscriptionCards = $cardFetcher->getSubscribed();

$subscriptionGrid = new VideoGrid($subscriptionCards, "subscriptions");
$html = $subscriptionGrid->render("Subscriptions");
if ($html) {
   echo $html;
} else {
   echo ErrorMsg::$noSubscriptions;
}

require_once("includes/footer.php");
?>