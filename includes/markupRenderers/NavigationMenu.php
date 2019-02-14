<?php
class NavigationMenu {

   private $user;

   public function __construct($user) {
      $this->user = $user;
   }

   public function render() {
      $html = $this->makeNavLink("Home", "assets/images/icons/home.png", "index.php");
      $html .= $this->makeNavLink("Trending", "assets/images/icons/trending.png", "trending.php");
      $html .= $this->makeNavLink("Subscriptions", "assets/images/icons/subscriptions.png", "subscriptions.php");
      $html .= $this->makeNavLink("Liked Videos", "assets/images/icons/thumb-up.png", "likedVideos.php");

      if (User::isLoggedIn()) {
         $html .= $this->makeNavLink("Settings", "assets/images/icons/settings.png", "settings.php");
         $html .= $this->makeNavLink("Log Out", "assets/images/icons/logout.png", "logout.php");

         $html .= "<span class='heading'>Subscriptions</span>";
         $html .= $this->makeSubscriptions();
      }

      return "
         <div class='navLinks'>
            $html
         </div>
      ";
   }

   private function makeNavLink($text, $icon, $to) {

      return "
         <div class='navLink'>
            <a href='$to'>
               <img src='$icon' alt='$text' name='text'>
               <span>$text</span>
            </a>
         </div>
      ";
   }

   private function makeSubscriptions() {
      $subscribers = $this->user->subscriptionsArray($objects = true);
      $html = "";

      foreach ($subscribers as $user) {
         $html .= $this->makeNavLink($user->username, $user->image, "profile.php?username=$user->username");
      }

      return $html;
   }

}
?>