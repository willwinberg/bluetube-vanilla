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
      $owner = var_dump($this->channelOwner);

      // $banner = $this->makeBanner();
      // $headerSection = $this->createHeaderSection();
      // $tabsSection = $this->createTabsSection();
      // $contentSection = $this->createContentSection();

      return "
         <div class='channelContainer'>
            $owner
         </div>
      ";
   }

public function makeBanner() {
      $src = $this->channelOwner->bannerImg;
      $name = $this->channelOwner->getFullName();

      return "
         <div class='bannerContainer'>
            <img src='$src' class='banner'>
            <span class='name'>$name</span>
         </div>
      ";
   }

   

}
?>