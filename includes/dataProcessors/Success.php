<?php

class Success {

   public static $login = "Login successful";
   public static $register = "Registration successful";
   public static $detailsUpdate = "Details update successful";
   public static $passwordUpdate = "Password update successful";
   
   public static $videoUpdate = "<div class='alert alert-success'>Video updated successfully</div>";

   function alert($message) {
      return "
         <div class='alert alert-success'>$message</div>
      ";
   }

}

?>