<?php
require_once("includes/header.php"); 

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$cardFetcher = new VideoCardsFetcher($db, $user);
$subscriptionCards = $cardFetcher->getSubscribed();

$subscriptionGrid = new VideoGrid($subscriptionCards, true);
echo $subscriptionGrid->render("Subscriptions");

require_once("includes/footer.php");
?>