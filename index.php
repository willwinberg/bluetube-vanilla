<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/VideoGrid.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoCard.css">

<div class="home">
    <?php
   $cardFetcher = new VideoCardsFetcher($db, $loggedInUser);


    if (User::isLoggedIn()) {
    $subbedCards = $cardFetcher->getSubscribed();
    $subscriptionsGrid = new VideoGrid($subbedCards);
    echo $subscriptionsGrid->render("Subscriptions");
    }

   $recommendedCards = $cardFetcher->getRecommended();
   $recommendedGrid = new VideoGrid($recommendedCards);
   echo $recommendedGrid->render("Recommendations");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>