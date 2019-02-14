<?php

class VideoGrid {

   private $cards, $type;

   public function __construct($cards, $type = false) {
      $this->cards = $cards;
      $this->type = $type;
      $this->cssClass = "videoGrid";

      if ($type) {
         $this->expanded = true;
         $this->cssClass .= " large";
      }
      if (($type === "results")) {
         $this->filterButtons = $this->makeFilterButtons();
      }
   }

   public function render($title = "") {
      $gridCards = $this->makeGridCards();
      $filterButtons = $this->filterButtons;
      
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
         $header
         <div class='$this->cssClass'>
            $gridCards
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

   public function makeFilterButtons() {
      $url = preg_replace("#&orderBy=.*#", '', $_SERVER['REQUEST_URI']);
      
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