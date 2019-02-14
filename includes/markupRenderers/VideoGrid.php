<?php

class VideoGrid {

   private $cards, $expanded;

   public function __construct($cards, $expanded) {
      $this->cards = $cards;
      $this->type = $type;
      $this->expanded = $expanded;
   }

   public function render($title, $filter) {
      $gridCards = $this->makeGridCards();

      if ($title) {
         $header = "
            <div class='gridHeader'>
               <div class='left'>
                  $title
               </div>
                $filter
            </div>
         ";
      } else {
         $header = "";
      }

      return "
         $header
         <div class='videoGrid'>
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


}
?>