<?php
require_once("includes/header.php");
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