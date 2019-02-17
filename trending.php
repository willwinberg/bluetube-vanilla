<?php
require_once("includes/header.php");

$cardFetcher = new VideoCardsFetcher($db, $user);
$trendingCards = $cardFetcher->getTrending();

$trendingGrid = new VideoGrid($trendingCards, true);
echo $trendingGrid->render("Trending");

require_once("includes/footer.php");
?>