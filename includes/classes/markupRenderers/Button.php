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

   public static function userProfileButton($username, $image) {
      $link = "profile.php?username=$username";

      return "
         <a href='$link'>
            <img src='$image' class='profilePicture'>
         </a>
      ";
   }

   public static function subscribeButton($dbConnection, $uploader, $subscriber) {
      $subscriptions = $subscriber->getSubscriptionsArray($uploader->username);
      $isSubscribed = in_array($subscriber->username);
      $text = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
      $text .= " " . $uploader->getSubscriberCount();

      $class = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
      $action = "subscribe(this, $uploader->username, $subscriber->username)";

      $button = Button::createButton($text, $action, $class, NULL);

      return "
         <div class='subscribeButtonContainer'>
            $button
      </div>";
   }

}
?>
