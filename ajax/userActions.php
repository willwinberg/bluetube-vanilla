<?php
require_once("../includes/config.php"); 
require_once("../includes/modelInterfaces/User.php"); 

if (isset($_POST["toUsername"]) && isset($_POST["fromUsername"])) {
   $toUser = new User($db, $_POST["toUsername"]);
   $fromUser = new User($db, $_POST["fromUsername"]);

   // Already subscribed
   if (in_array($toUser->username, $fromUser->subscriptionsArray())) {
      $fromUser->unsubscribe($toUser->username);
   } else {
      $fromUser->subscribe($toUser->username);
   }
   
   echo $toUser->subscriberCount();
} else {
   echo "Error in userAction.php";
}
?>
