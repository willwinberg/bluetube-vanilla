<?php
require_once("includes/header.php");
?>

<div class="videoSection">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);

   $subbedCards = $cardFetcher->getSubscribed();
   $subscriptionsGrid = new VideoGrid($subbedCards);
   echo $subscriptionsGrid->render("Subscriptions");
   
   $recommendedCards = $cardFetcher->getRecommended();
   $recommendedGrid = new VideoGrid($recommendedCards);
   echo $recommendedGrid->render("Recommendations");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>