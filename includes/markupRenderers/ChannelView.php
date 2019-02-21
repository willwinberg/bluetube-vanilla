<?php

class ChannelView {

private $db, $loggedInUser, $channelsUsername;

   public function __construct($db, $loggedInUser, $channelUsername) {
      $this->db = $db;
      $this->loggedInUser = $loggedInUser;

      if (!$channelUsername) {
         return "Channel does not exist";
      } else {
         $this->channelOwner = new User($db, $channelUsername);
      }
   }

   public function render() {
      $banner = $this->makeBanner();
      $header = $this->makeHeader();
      $tabs = $this->makeTabs();
      $videos = $this->makeVideos();
      $about = $this->makeAbout();

      return "
         <div class='channel'>
            $banner
            $header
            $tabs
            <div class='tab-content content'>
               $videos
               $about
            </div>
         </div>
      ";
   }

   private function makeBanner() {
      $src = $this->channelOwner->bannerImg;
      $username = $this->channelOwner->username;

      return "
         <div class='banner'>
            <img src='$src' alt='profile banner'>
            <span class='username'>$username</span>
         </div>
      ";
   }

   private function makeHeader() {
      $image = $this->channelOwner->image;
      $name = $this->channelOwner->fullName();
      $subscriberCount = $this->channelOwner->getSubscriberCount();
      $subscribeButton = $this->makeSubscribeButton();

      return "
         <div class='header'>
            <div class='info'>
               <img src='$image' alt='owner picture'>
               <div class='bottom'>
                     <span class='name'>$name</span>
                     <span class='subscribes'>$subscriberCount subscribers</span>
               </div>
            </div> 
            <div class='buttonContainer'>
               $subscribeButton
            </div>
         </div>
      ";
   }

   private function makeSubscribeButton() {
      if ($this->channelOwner->username !== $this->loggedInUser->username) {
         return Button::subscribeButton($this->db, $this->loggedInUser, $this->channelOwner->username);
      } else {
         return Button::hyperlink("Edit My Profile", "settings.php", "edit", null);
      }
   }

   private function makeTabs() { // Bootstrap
      return "
         <ul class='nav nav-tabs' role='tablist'>
            <li class='nav-item'>
            <a
               class='nav-link active'
               id='videos-tab'
               data-toggle='tab' 
               href='#videos'
               role='tab'
               aria-controls='videos'
               aria-selected='true'
            >
               VIDEOS
            </a>
            </li>
            <li class='nav-item'>
            <a
            class='nav-link'
            id='about-tab'
            data-toggle='tab'
            href='#about'
            role='tab' 
            aria-controls='about'
            aria-selected='false'
            >
               ABOUT
            </a>
            </li>
         </ul>
      ";
   }

   private function makeVideos() {
      $cardFetcher = new VideoCardsFetcher($this->db, $this->channelOwner);
      $ownedVideos = $cardFetcher->getOwned();
      $ownerUsername = $this->channelOwner->username;

      if (sizeof($ownedVideos) > 0) {
         $ownedGrid = new VideoGrid($ownedVideos);
         $html = $ownedGrid->render("$ownerUsername's Videos");
      } else {
         $html = "<span>$ownerUsername has not uploaded any videos</span>";
      }

      return "
            <div
               class='tab-pane fade show active'
               id='videos' role='tabpanel'
               aria-labelledby='videos-tab'
            >
               $html
            </div>
      ";
   }

   private function makeAbout() {
      $name = $this->channelOwner->fullName();
      $username = $this->channelOwner->username;
      $subscriberCount = $this->channelOwner->getSubscriberCount();
      $totalViews = $this->channelOwner->getTotalViews();
      $signUpDate = $this->channelOwner->signUpDate();

      return "
         <div
            class='tab-pane fade'
            id='about' role='tabpanel'
            aria-labelledby='about-tab'
         >
            <div class='section'>
               <div class='title'>
                  <span>Details</span>
               </div>
               <div class='values'>
                  <span>Name: $name</span>
                  <span>Username: $username</span>
                  <span>Subscribers: $subscriberCount</span>
                  <span>Total Views: $totalViews</span>
                  <span>Sign-up Date: $signUpDate</span>
               </div>
            </div>
         </div>
      ";
   }

}
?>