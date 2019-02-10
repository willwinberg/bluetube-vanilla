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
      var_dump($username);

      return "
         <a href='$link'>
            <img src='$image' class='profilePicture'>
         </a>
      ";
   }

   public static function editVideoButton($videoId) {
      $link = "editVideo.php?videoId=$videoId";
      $button = Button::hyperlink("EDIT VIDEO", $link, "edit button", NULL);

      return "
         <div class='editVideoButtonContainer'>
            $button
         </div>
      ";
    }

   public static function subscribeButton($uploader, $subscriber) {
      $subscriptions = $subscriber->subscriptionsArray($uploader->username);
      $isSubscribed = in_array($subscriber->username, $subscriptions);
      $text = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
      $text .= " " . $uploader->subscriberCount();

      $class = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
      $action = "subscribe(this, $uploader->username, $subscriber->username)";

      $button = Button::regular($text, $action, $class, NULL);

      return "
         <div class='subscribeButtonContainer'>
            $button
         </div>
      ";
   }

}
?>
