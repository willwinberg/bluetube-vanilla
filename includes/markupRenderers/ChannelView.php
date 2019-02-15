<?php

class ChannelView {

private $db, $user, $channelsUsername;

   public function __construct($db, $user, $channelUsername) {
      $this->db = $db;
      $this->user = $user;

      if (!$channelUsername) {
         return "Channel does not exist";
      } else {
         $this->channelOwner = new User($db, $channelUsername);
      }
   }

   public function render() {
      $owner = var_dump($this->channelOwner);
      return "
         <div class='channelContainer'>
            $owner
         </div>
      ";
   }

}
?>