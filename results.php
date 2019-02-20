<?php
require_once("includes/header.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");

$term = FormInputSanitizer::sanitize($_GET["term"]);
$orderParam = $_GET["orderBy"];

if(!isset($orderParam) || $orderParam === "views") {
    $orderBy = "views";
} else {
    $orderBy = "uploadDate";
}
?>


   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $searchResultCards = $cardFetcher->getSearchResults($term, $orderBy);
   $searchResultsGrid = new VideoGrid($searchResultCards, "results");
   $length = sizeof($searchResultCards);
   $plural = $length > 1 ? "s" : "";

   echo $searchResultsGrid->render("Your search  for \"$term\" returned $length result$plural");
   ?>


<?php require_once("includes/footer.php"); ?>