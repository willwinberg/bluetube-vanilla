<?php

require_once("includes/config.php");
require_once("includes/classes/processors/FormInputSanitizer.php"); 
require_once("includes/classes/processors/FormInputValidator.php");
require_once("includes/classes/processors/UserEntryHandler.php");
require_once("includes/classes/processors/ErrorMessage.php"); 

$newUserDataSanitizer = new FormInputSanitizer;
$newUserDataValidator = new formInputValidator($dbConnection);
$newUserAccount = new UserEntryHandler($dbConnection);

if (isset($_POST["submitRegisterForm"])) {

   $sanitizedUserData = $newUserDataSanitizer->sanitizeNewUserData($_POST);

   $newUserDataValidator->validateNewUserData($sanitizedUserData);

   $noErrors = empty($newUserDataValidator->errorArray);

   if ($noErrors) {
      $newUserAccount->registerNewUser($sanitizedUserData);
      $_SESSION["loggedIn"] = $sanitizedUserData["username"];
      header("Location: index.php");
   }
}

function getValue($key) {
   if (isset($_POST[$key])) {
      echo $_POST[$key];
   }
}
?>

<?php require_once("includes/entryHeader.php"); ?>

<div class="entryContainer">
   <div class="column">
      <div class="entryHeader">
         <img src="assets/images/logo.png" title="logo" alt="BlueTube Logo"/>
         <h3>Sign Up</h3>
         <span>to continue to BlueTube</span>
      </div>
      <div class="entryForm">
         <form action="register.php" method="POST">

            <input
               required
               type="text"
               name="firstName"
               value="<?php getValue('firstName'); ?>"
               placeholder="First name"
            >
            <?php echo $newUserDataValidator->getError(ErrorMessage::$firstNameLength); ?>

            <input
               required
               type="text"
               name="lastName"
               value="<?php getValue('lastName'); ?>"
               placeholder="Last name"
            >
            <?php echo $newUserDataValidator->getError(ErrorMessage::$lastNameLength); ?>

            <input
               required
               type="text"
               name="username"
               value="<?php getValue('username'); ?>"
               placeholder="Username"
            >
            <?php echo $newUserDataValidator->getError(ErrorMessage::$usernameLength); ?>
            <?php echo $newUserDataValidator->getError(ErrorMessage::$usernameTaken); ?>

            <input
               required
               type="email" 
               name="email"
               value="<?php getValue('email'); ?>"
               placeholder="Email"
            >
            <?php echo $newUserDataValidator->getError(ErrorMessage::$emailInvalid); ?>
            <?php echo $newUserDataValidator->getError(ErrorMessage::$emailTaken); ?>

            <input
               required
               type="email"
               name="emailConfirm"
               value="<?php getValue('emailConfirm'); ?>"
               placeholder="Confirm email"
            >
            <?php echo $newUserDataValidator->getError(ErrorMessage::$emailsDoNotMatch); ?>

            <input
               required
               type="password"
               name="password"
               placeholder="Password"
            >
            <?php echo $newUserDataValidator->getError(ErrorMessage::$passwordInsecure); ?>
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

<?php require_once("includes/entryFooter.php"); ?>
