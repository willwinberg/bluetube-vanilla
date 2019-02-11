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

   public static function profileButton($username, $image) {
      $link = "profile.php?username=$username";

      return "
         <a href='$link'>
            <img src='$image' class='profileImg'>
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

   public static function subscribeButton($uploader, $subscriber) {
      $subscriptions = $uploader->subscriptionsArray();
      $isSubscribed = in_array($subscriber->username, $subscriptions);
      $text = $isSubscribed ? "SUBSCRIBED" : "SUBSCRIBE";
      $text .= " " . sizeof($subscriptions);

      $class = $isSubscribed ? "unsubscribe button" : "subscribe button";
      $action = "subscribe(this, \"$uploader->username\", \"$subscriber->username\")";

      $button = Button::regular($text, $action, $class, NULL);

      return "
         <div class='buttonContainer'>
            $button
         </div>
      ";
   }

}
?>
