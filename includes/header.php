<?php
require_once("includes/config.php");
require_once("includes/modelInterfaces/User.php");
require_once("includes/modelInterfaces/Video.php");
require_once("includes/dataProcessors/VideoCardsFetcher.php");
require_once("includes/markupRenderers/VideoGrid.php"); 
require_once("includes/markupRenderers/VideoCard.php");

$loggedInUsername = User::loggedIn() ? $_SESSION["loggedIn"] : "";
$user = new User($db, $loggedInUsername);
echo $user->firstName;
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>BlueTube</title>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
   <link rel="stylesheet" type="text/css" href="assets/css/style.css">
   <link rel="icon" type="image/png" href="assets/images/icons/w.ico">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
   <script src="assets/javascript/commonActions.js"></script>
</head>

<body>
   <div id="pageContainer">
      <div id="mastHeadContainer">
         <button class="navShowHide">
            <img src="assets/images/icons/menu.png" />
         </button>
         <a class="logoContainer" href="index.php">
            <img src="assets/images/logo.png" title="logo" alt="BlueTube Logo"/>
         </a>
         <div class="searchBarContainer">
            <form action="search.php" method="GET">
               <input type="text" class="searchBar" name="term" placeholder="Search...">
               <button class="searchButton">
                  <img src="assets/images/icons/search.png">
               </button>
            </form>
         </div>
         <div class="rightIcons">
            <a href="upload.php">
               <img class="upload" src="assets/images/icons/upload.png">
            </a>
            <a href="#">
               <img class="upload" src="assets/images/profilePictures/default.png">
               <?php echo $loggedInUsername ?>
            </a>
         </div>
      </div>
      <div id="sideNavContainer" style="display: none">
      </div>
      <div id="mainSectionContainer">
         <div id="mainContentContainer">
            