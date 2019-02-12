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

   public static function hyperlink($text, $link, $class, $src) {
      $val = $text || $class;
      $image = $src ? "<img src='$src' name='$val' alt='$val'>" : "";

      return "
         <a href='$link'>
            <button class='$class'>
               $image
               <span class='text'>$text</span>
            </button>
         </a>
      ";
    }

   public static function profileButton($db, $username) {
      $user = new User($db, $username);
      $link = "profile.php?username=$username";
      
      return "
         <a href='$link'>
            <img src='$user->image' class='profileImg'>
         </a>
      ";
   }

   public static function editVideoButton($videoId) {
      $link = "editVideo.php?videoId=$videoId";
      $button = Button::hyperlink("EDIT VIDEO", $link, "edit button", NULL);

      return "
         <div class='buttonContainer'>
            $button
         </div>
      ";
    }

   public static function subscribeButton($db, $subscriberUsername, $uploader) {
      $subscribingTo = new User($db, $uploader);

      $existingSubs = $subscribingTo->subscriptionsArray();
      $alreadySubbed = in_array($subscriberUsername, $existingSubs);
      $text = $alreadySubbed ? "SUBSCRIBED" : "SUBSCRIBE";
      $text .= " " . sizeof($existingSubs);

      $class = $alreadySubbed ? "unsubscribe button" : "subscribe button";
      $action = "subscribe(this, \"$subscribingTo->username\", \"$subscriberUsername\")";

      $button = Button::regular($text, $action, $class, NULL);

      return "
         <div class='buttonContainer'>
            $button
         </div>
      ";
   }

}
?>
