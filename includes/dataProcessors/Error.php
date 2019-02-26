<?php

class Error {

   public static $firstNameLength = "First name must be between 2 and 30 characters";
   public static $lastNameLength = "Last name must be between 2 and 30 characters";
   public static $usernameLength = "Username must be between 5 and 20 characters";
   public static $usernameChars = "Username may only consist of letters and numbers";
   public static $usernameTaken = "Username taken";
   public static $emailInvalid = "Invalid email address";
   public static $emailsDoNotMatch = "Emails do not match";
   public static $emailTaken = "Email already in use";
   public static $passwordsDoNotMatch = "Passwords do not match";
   public static $passwordNotSecure= "Password must contain at least one lower case letter, one upper case letter and one digit";
   public static $passwordLength = "Password must be between 8 and 30 characters";
   public static $passwordIncorrect = "You've entered an incorrect password";
   public static $register = "A fatal error ocurred during registration";

   public static $login = "Invalid username and password";

   public static $noChanges = "<div class='alert alert-warning'>No Changes have been detected</div>";
   public static $videoUpdate = "<div class='alert alert-danger'>Your video update failed</div>";
   public static $upload = "<div class='alert alert-danger'>Video upload failed</div>";
   public static $detailsUpdate = "Your details update failed";
   public static $passwordUpdate = "Your password update failed";
   public static $noVideoSelected = "No video has been selected";
   public static $notOwnedVideo = "You do not have permission to edit this video";

   public static $noSubscriptions = "<span class='errorMessage'>You haven't subscribed to anyone yet.</span>";
   public static $noLiked = "<span class='errorMessage'>You haven't liked any videos yet.</span>";

   public static $image = "Error uploading image";
   public static $notImage = "File is not an image";
   public static $imageSize = "File must be under 5MB";
   public static $imageType = "Only JPG, JPEG, PNG & GIF files are allowed";
   public static $imageInvalid = "The designated file is invalid";

   public static function alert($error) {
      return "
         <div class='alert alert-danger'>$error</div>
      ";
   }

}

?>