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
         <div class='channelContainer'>
            $banner
            $header
            $tabs
            <div class='tab-content channelContent'>
               $videos
               $about
            </div>
         </div>
      ";
   }

   public function makeBanner() {
      $src = $this->channelOwner->bannerImg;
      $name = $this->channelOwner->fullName();

      return "
         <div class='bannerContainer'>
            <img src='$src' class='banner'>
            <span class='channelName'>$name</span>
         </div>
      ";
   }

   public function makeHeader() {
      $image = $this->channelOwner->image;
      $name = $this->channelOwner->fullName();
      $subscriberCount = $this->channelOwner->getSubscriberCount();
      $subscribeButton = Button::subscribeButton($this->db, $this->loggedInUser, $this->channelOwner->username);

      return "
         <div class='channelHeader'>
            <div class='infoContainer'>
               <img class='ownerImage' src='$image'>
               <div class='ownerInfo'>
                     <span class='name'>$name</span>
                     <span class='subscriberCount'>$subscriberCount subscribers</span>
               </div>
            </div> 
            $subscribeButton
         </div>
      ";
   }

   public function makeTabs() { // Bootstrap
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

   public function makeVideos() {
      $cardFetcher = new VideoCardsFetcher($this->db, $this->channelOwner);
      $ownedVideos = $cardFetcher->getOwned();
      $ownerUsername = $this->channelOwner->username;

      if (sizeof($ownedVideos) > 0) {
         $ownedGrid = new VideoGrid($ownedVideos, true);
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
               $gridHtml
            </div>
      ";
   }

   private function makeAbout() {
      $name = $this->channelOwner->fullName();
      $username = $this->channelOwner->username;
      $subscriberCount = $this->channelOwner->getSubscriberCount();
      $totalViews = $this->channelOwner->getTotalViews();
      $signUpDate = $this->channelOwner->signUpDate;

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
                  <span>Subscribers: $subscriberCount-></span>
                  <span>Total Views: $totalViews</span>
                  <span>Sign-up Date: $signUpDate</span>
               </div>
            </div>
         </div>
      ";
   }


}
?>