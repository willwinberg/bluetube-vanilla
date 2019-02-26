<?php

class VideoGrid {

   private $cards, $type;

   public function __construct($cards, $type = false) {
      $this->cards = $cards;
      $this->expanded = false;
      $this->cssClass = "videoGrid";

      switch ($type) {
         case "watchPage":
            $this->cssClass .= " asRows";
            break;
         case true:
            $this->expanded = true;
            $this->cssClass .= " large";
      }
   }

   public function render($title = "", $filterButtons = false) {
      $gridCards = $this->makeGridCards();
      if (!$gridCards) return "";
      if ($filterButtons) {
         $filterButtons = $this->makeFilterButtons();
      }

      if ($title) {
         $header = "
            <div class='gridHeader'>
               <div class='left'>
                  $title
               </div>
               $filterButtons
            </div>
         ";
      } else {
         $header = "";
      }

      return "
         <div class=videoGridContainer>
            $header
            <div class='$this->cssClass'>
               $gridCards
            </div>
         </div>
      ";
   }

   private function makeGridCards() {
      $html = "";

      foreach ($this->cards as $card) {
         $card->setExpanded($this->expanded);
         $html .= $card->render();
      }

      return $html;
   }

   private function makeFilterButtons() {
      $url = preg_replace("#&orderBy=.*#", '', $_SERVER['REQUEST_URI']);
      echo $url;
      
      return "
         <div class='right'>
            <span>Order by:</span>
            <a href='$url&orderBy=uploadDate'>Upload date</a>
            <a href='$url&orderBy=views'>Most viewed</a>
         </div>
      "; 
   }

}
?>