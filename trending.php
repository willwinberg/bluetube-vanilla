<link rel="stylesheet" type="text/css" href="assets/css/VideoGrid.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoCard.css">
<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");

$cardFetcher = new VideoCardsFetcher($db, $user);
$trendingCards = $cardFetcher->getTrending();

$trendingGrid = new VideoGrid($trendingCards, true);
echo $trendingGrid->render("Trending");

require_once("includes/footer.php");
?>