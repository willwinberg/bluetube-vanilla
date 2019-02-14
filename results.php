<?php
require_once("includes/header.php");

$term = $_GET["term"];

if (isset($_GET["orderBy"])) {
   $orderBy = "uploadDate";
} else {
   $orderBy = "views";
}
?>

<div class="searchResultsContainer">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $searchResultCards = $cardFetcher->getSearchResults($term, $orderBy);
   $searchResultsGrid = new VideoGrid($searchResultCards, true);
   $length = sizeof($searchResultCards);
   echo $searchResultsGrid->render("Your search returned $length results");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>