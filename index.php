<?php require_once("includes/header.php");?>

<div class="home">
    <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);


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