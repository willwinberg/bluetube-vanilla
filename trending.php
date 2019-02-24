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

$cardFetcher = new VideoCardsFetcher($db, $loggedInUser);
$trendingCards = $cardFetcher->getTrending();

$trendingGrid = new VideoGrid($trendingCards, "trending");
echo $trendingGrid->render("Trending");

require_once("includes/footer.php");
?>