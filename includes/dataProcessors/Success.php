<?php

class Success {

   public static $login = "Login successful";
   public static $register = "Registration successful";
   public static $detailsUpdate = "Details update successful";
   public static $passwordUpdate = "Password update successful";
   
   public static $videoUpdate = "<div class='alert alert-success'>Video updated successfully</div>";
   public static $upload = "<div class='alert alert-success'>Video upload successful</div>";
   public static $image = "<div class='alert alert-success'>Image update successful</div>";


   function alert($message) {
      return "
         <div class='alert alert-success'>$message</div>
      ";
   }

}

?>