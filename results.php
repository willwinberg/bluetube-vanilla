<?php
require_once("includes/header.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/VideoGrid.css">
<link rel="stylesheet" type="text/css" href="assets/css/VideoCard.css">
<?php

$term = FormInputSanitizer::sanitize($_GET["term"]);
$orderParam = $_GET["orderBy"];

if(!isset($orderParam) || $orderParam === "views") {
    $orderBy = "views";
} else {
    $orderBy = "uploadDate";
}
?>


   <?php
   $cardFetcher = new VideoCardsFetcher($db, $loggedInUser);
   
   $searchResultCards = $cardFetcher->getSearchResults($term, $orderBy);
   $searchResultsGrid = new VideoGrid($searchResultCards, "results");
   $length = sizeof($searchResultCards);
   $plural = $length > 1 ? "s" : "";

   echo $searchResultsGrid->render("Your search  for \"$term\" returned $length result$plural");
   ?>


<?php require_once("includes/footer.php"); ?>