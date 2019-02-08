<?php

class Button {

   public static function createLink($link) {
      return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
   }


   public static function regular($text, $action, $class, $src) {
      $val = $text || $class;
      $image = $src ? "<img src='$src' name='$val' alt='$val'>" : "";
      // $action  = ButtonProvider::createLink($action);

      return "
         <button class='$class' onclick='$action'>
            $image
            <span class='text'>$text</span>
         </button>
      ";
   }

}
?>
