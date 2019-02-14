<?php
require_once("includes/header.php");

$term = $_GET["term"];

if (isset($_GET["orderBy"])) {
   $orderBy = "uploadDate";
} else {
   $orderBy = "views";
}
?>

<div class="searchResults">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $searchResultCards = $cardFetcher->getSearchResults($term, $orderBy);
   $searchResultsGrid = new VideoGrid($searchResultCards, true);
   echo $searchResultsGrid->render("Search Results", NULL);
   ?>
</div>

<?php require_once("includes/footer.php"); ?>