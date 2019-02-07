<?php

require_once("includes/config.php");
require_once("includes/classes/FormInputSanitizer.php"); 
require_once("includes/classes/FormInputValidator.php"); 
require_once("includes/classes/ErrorMessage.php"); 

$newUserDataSanitizer = new FormInputSanitizer;
$newUserDataValidator = new formInputValidator($dbConnection);

if (isset($_POST["submitRegisterForm"])) {

   $sanitizedUserData = $newUserDataSanitizer->sanitizeNewUserData($_POST);

   $newUserDataValidator->validateNewUserData($sanitizedUserData);

   var_dump($sanitizedUserData); // REMOVE
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>BlueTube</title>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="assets/css/style.css">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</head>

<body>
   <div class="entryContainer">
      <div class="column">
         <div class="entryHeader">
            <img src="assets/images/logo.png" title="logo" alt="BlueTube Logo"/>
            <h3>Sign Up</h3>
            <span>to continue to VideoTube</span>
         </div>
         <div class="entryForm">
            <form action="register.php" method="POST">

               <input
                  required
                  type="text"
                  name="firstName"
                  placeholder="First name"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$firstNameLength); ?>

               <input
                  required
                  type="text"
                  name="lastName"
                  placeholder="Last name"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$lastNameLength); ?>

               <input
                  required
                  type="text"
                  name="username"
                  placeholder="Username"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$usernameLength); ?>
               <?php echo $newUserDataValidator->getError(ErrorMessage::$usernameTaken); ?>

               <input
                  required
                  type="email"
                  name="email"
                  placeholder="Email"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$emailInvalid); ?>
               <?php echo $newUserDataValidator->getError(ErrorMessage::$emailTaken); ?>

               <input
                  required
                  type="email"
                  name="emailConfirm"
                  placeholder="Confirm email"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$emailsDoNotMatch); ?>

               <input
                  required
                  type="password"
                  name="password"
                  placeholder="Password"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$passwordNotAlphanumeric); ?>
               <?php echo $newUserDataValidator->getError(ErrorMessage::$passwordLength); ?>

               <input
                  required
                  type="password"
                  name="passwordConfirm"
                  placeholder="Confirm password"
               >
               <?php echo $newUserDataValidator->getError(ErrorMessage::$passwordsDoNotMatch); ?>

               <input type="submit" name="submitRegisterForm" value="SUBMIT">
            </form>
         </div>
         <a class="entryMessage" href="login.php">Already have an account? Log in here.</a>
      </div>
   </div>
</body>
</html>