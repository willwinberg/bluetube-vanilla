<?php

class VideoGrid {

   private $cards, $expanded;

   public function __construct($cards, $expanded = false) {
      $this->cards = $cards;
      $this->expanded = $expanded;
      $this->cssClass = "videoGrid";

      if ($expanded) {
         $this->filtersHeader = $this->makeFiltersHeader();
      } 
   }

   public function render($title) {
      $gridCards = $this->makeGridCards();

      if ($title) {
         $header = "
            <div class='gridHeader'>
               <div class='left'>
                  $title
               </div>
               $this->filtersHeader
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

   public function makeFiltersHeader() {
      $this->cssClass .= " large";

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