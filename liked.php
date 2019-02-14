<?php
require_once("includes/header.php"); 

if (!User::isLoggedIn()) {
   header("Location: login.php");
}
?>

<div class="likedContainer">
   <?php
   $cardFetcher = new VideoCardsFetcher($db, $user);
   
   $likedCards = $cardFetcher->getLiked();
   $likedGrid = new VideoGrid($likedCards, true);
   echo $likedGrid->render("Videos You Liked");
   ?>
</div>

<?php require_once("includes/footer.php"); ?>