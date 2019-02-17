<?php
require_once("includes/header.php"); 

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$cardFetcher = new VideoCardsFetcher($db, $user);
$likedCards = $cardFetcher->getLiked();

$likedGrid = new VideoGrid($likedCards, true);
echo $likedGrid->render("Videos You Liked");

require_once("includes/footer.php"); 
?>