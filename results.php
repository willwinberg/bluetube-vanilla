<?php
require_once("includes/header.php");

$term = $_GET["term"];
$orderParam = $_GET["orderBy"];

if(!isset($orderParam) || $orderParam === "views") {
    $orderBy = "views";
} else {
    $orderBy = "uploadDate";
}
?>

<div class="searchResultsContainer">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $searchResultCards = $cardFetcher->getSearchResults($term, $orderBy);
   $searchResultsGrid = new VideoGrid($searchResultCards, "results");
   $length = sizeof($searchResultCards);
   echo $searchResultsGrid->render("Your search returned $length results");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>