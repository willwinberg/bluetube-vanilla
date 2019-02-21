<link rel="stylesheet" type="text/css" href="assets/css/Button.css">

<?php

class Button {

   public static function getAction($link) {
      if (User::isLoggedIn()) {
         return $link;
      }
      return "notSignedInAlert()";
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
      $val = $text ? $text : $class;
      $image = $src ? "<img src='$src' title='$val' alt='$val'>" : "";

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
      $link = "channel.php?username=$username";
      $img = $user->image();
      
      return "
         <a href='$link'>
            <img src='$img' class='profileImg'>
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

   public static function subscribeButton($db, $subscriber, $uploader) {
      $uploader = new User($db, $uploader);
      $uploaderUsername = $uploader->username();
      $uploaderSubCount = $uploader->getSubscriberCount();
      
      $subscriberUsername = $subscriber->username();
      $subscribersSubs = $subscriber->subscriptionsArray();

      $alreadySubbed = in_array($uploaderUsername, $subscribersSubs);

      $text = $alreadySubbed ? "SUBSCRIBED" : "SUBSCRIBE";
      $text .= " " . $uploaderSubCount;

      $class = $alreadySubbed ? "unsubscribe button" : "subscribe button";
      $action = "subscribe(this, \"$uploaderUsername\", \"$subscriberUsername\")";

      $button = Button::regular($text, $action, $class, NULL);

      return "
         <div class='buttonContainer'>
            $button
         </div>
      ";
   }

}
?>
