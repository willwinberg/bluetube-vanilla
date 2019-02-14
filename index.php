<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");

?>

<div class="videoSection">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);

   $subbedCards = $cardFetcher->getSubscribed();
   $subscriptionsGrid = new VideoGrid($subbedCards, false);
   echo $subscriptionsGrid->render("Subscriptions", NULL);
   
   $recommendedCards = $cardFetcher->getRecommended();
   $recommendedGrid = new VideoGrid($recommendedCards, false);
   echo $recommendedGrid->render("Recommendations", NULL);
   ?>
</div>

<?php require_once("includes/footer.php"); ?>