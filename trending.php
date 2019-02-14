<?php require_once("includes/header.php"); ?>

<div class="trendingContainer">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $trendingCards = $cardFetcher->getTrending();
   $trendingGrid = new VideoGrid($trendingCards, true);
   echo $trendingGrid->render("Trending");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>