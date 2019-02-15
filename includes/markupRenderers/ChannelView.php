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
      // $headerSection = $this->createHeaderSection();
      // $tabsSection = $this->createTabsSection();
      // $contentSection = $this->createContentSection();

      return "
         <div class='channelContainer'>
            $banner
            $header
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

   

}
?>