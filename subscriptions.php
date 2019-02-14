<?php require_once("includes/header.php"); ?>

<div class="subscriptionsContainer">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $subscriptionCards = $cardFetcher->getSubscribed();
   $subscriptionGrid = new VideoGrid($subscriptionCards, true);
   echo $subscriptionGrid->render("Subscriptions");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>