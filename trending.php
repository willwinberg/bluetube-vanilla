<?php
require_once("includes/header.php");

if (isset($_GET["orderBy"])) {
   $orderBy = "uploadDate";
} else {
   $orderBy = "views";
}
?>

<div class="trendingContainer">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $trendingCards = $cardFetcher->getTrending();
   $trendingGrid = new VideoGrid($trendingCards, true);
   echo $trendingGrid->render("Trending");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>