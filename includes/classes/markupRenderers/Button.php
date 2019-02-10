<?php

class Button {

   public static $rerouteToSIgnIn = "notSignedInAlert()";

   public static function getAction($link) {
        return User::isLoggedIn() ? $link : Button::$rerouteToSIgnIn;
    }

   public static function regular($text, $action, $class, $src) {
      $val = $text || $class;
      $image = $src ? "<img src='$src' name='$val' alt='$val'>" : "";
      $action = Button::getAction($action);

      return "
         <button class='$class' onclick='$action'>
            $image
            <span class='text'>$text</span>
         </button>
      ";
   }

}
?>
