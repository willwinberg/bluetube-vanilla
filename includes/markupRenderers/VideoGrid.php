<?php

class VideoGrid {

   private $db, $user, $expanded;
   private $cssClass = "videoGrid";

   public function __construct($db, $user, $expanded) {
      $this->db = $db;
      $this->user = $user;
      $this->expanded = $expanded;
   }

   public function render($title, $filter) {
      $gridCards = $this->gridCards();

      if ($title) {
         $header = $this->createGridHeader($title, $filter);
      } else {
         $header = "";
      }

      return "
         $header
         <div class='$this->gridClass'>
            $gridCards
         </div>
      ";
   }

   private function gridCards() {
      if ($videos) {
         $gridCards = $this->createGridCards($videos);
      } else {
         $gridCards = $this->getGridCards();
      }

      return $gridCards;
   }
   
   private function getGridCards() {
      $query = $this->db->prepare(
         "SELECT * FROM videos ORDER BY RAND() LIMIT 15");
      $query->execute();
      $html = "";

      while ($video = $query->fetch(PDO::FETCH_ASSOC)) {
         $card = new VideoCard($this->db, $video, $this->user);
         $card->setExpanded($this->expanded);
         $html .= $card->render();
      }

      return $html;
   }

   public function createGridCards($videos) {
      
   }
   
   public function createGridHeader($title, $filter) {
      return "";
   }

}
?>