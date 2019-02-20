<link rel="stylesheet" type="text/css" href="assets/css/Masthead.css">
<?php
require_once("includes/markupRenderers/Button.php");

class Masthead {

   public function __construct ($db, $loggedInUsername) {
      $this->db = $db;
      $this->username = $loggedInUsername;

   }

   public function render() {
      $navButton = $this->makeNavButton();
      $logo = $this->makeLogo();
      $searchBar = $this->makeSearchBar();
      $uploadButton = Button::hyperlink("", "upload.php", "icon", "assets/images/icons/upload.png");
      $profileButton = $this->makeProfileNavButton();

      return "
         <div id='mastheadContainer'>
            $navButton
            $logo
            $searchBar
            <div class='rightIcons'>
               $uploadButton
               $profileButton
            </div>
         </div>
      ";
   }

   private function makeNavButton() {
      return "
         <button class='navShowHide'>
            <img src='assets/images/icons/menu.png'>
         </button>
      ";
   }

   private function makeLogo() {
      return "
         <a class='logo' href='index.php'>
            <img src='assets/images/logo.png' title='logo' alt='Site logo'>
         </a>
      ";
   }

   private function makeSearchBar() {
      return "
         <div class='searchBar'>
            <form action='results.php' method='GET'>
               <input
                  type='text'
                  name='term'
                  placeholder='Search...'
                  required
               >
               <button>
                  <img src='assets/images/icons/search.png'>
               </button>
            </form>
         </div>
      ";
   }

   private function makeProfileNavButton() {
      if (User::isLoggedIn()) {
         return Button::profileButton($this->db, $this->username);
      } else {
         return "
            <a href='login.php'>
               <span class='signInLink'>LOG IN</span>
            </a>
         ";
      }
   }

}
?>