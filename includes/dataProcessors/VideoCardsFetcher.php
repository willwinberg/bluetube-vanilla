<?php

class VideoCardsFetcher {

   private $db, $user, $expanded;

   public function __construct($db, $user) {
      $this->db = $db;
      $this->user = $user;
   }
   
   // Just random for now
   public function getRecommended() {
      $query = $this->db->prepare(
         "SELECT * FROM videos ORDER BY RAND() LIMIT 15");
      $query->execute();
      $cards = array();

      while ($video = $query->fetch(PDO::FETCH_ASSOC)) {
         $card = new VideoCard($this->db, $video, $this->user);
         array_push($cards, $card);
      }

      return $cards;
   }

   public function getSubscribed() {
      $subbedUsernames = $this->user->subscriptionsArray();
      $length = sizeof($subbedUsernames);

      if ($length > 0) {
         $sql = "WHERE uploadedBy=?";
         $i = 1;

         while ($i < $length) {
            $sql .= " OR uploadedBy=?";
         }
      }

      $query = $this->db->prepare(
         "SELECT * FROM videos $sql ORDER BY uploadDate DESC"
      );
      $i = 1;
      foreach ($subbedUsernames as $username) {
         $query->bindValue($i, $username);
         $i++;
      }
      $query->execute();
      $cards = array();

      while ($video = $query->fetch(PDO::FETCH_ASSOC)) {
         $card = new VideoCard($this->db, $video, $this->user);
         array_push($cards, $card);
      }

      return $cards;
   }
}
?>