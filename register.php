<?php

require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php"); 
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/UserEntryHandler.php");
require_once("includes/dataProcessors/ErrorMessage.php"); 

$userDataSanitizer = new FormInputSanitizer;
$userDataValidator = new formInputValidator($db);
$entry = new UserEntryHandler($db);

if (isset($_POST["submitRegisterForm"])) {
   $sanitizedUserData = $userDataSanitizer->sanitize($_POST);

   $userDataValidator->validateNewUserData($sanitizedUserData);
   $noErrors = empty($userDataValidator->errorArray);

   if ($noErrors) {
      $entry->registerNewUser($sanitizedUserData);
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
            <?php echo $userDataValidator->getError(ErrorMessage::$firstNameLength); ?>

            <input
               required
               type="text"
               name="lastName"
               value="<?php getValue('lastName'); ?>"
               placeholder="Last name"
            >
            <?php echo $userDataValidator->getError(ErrorMessage::$lastNameLength); ?>

            <input
               required
               type="text"
               name="username"
               value="<?php getValue('username'); ?>"
               placeholder="Username"
            >
            <?php echo $userDataValidator->getError(ErrorMessage::$usernameLength); ?>
            <?php echo $userDataValidator->getError(ErrorMessage::$usernameTaken); ?>

            <input
               required
               type="email" 
               name="email"
               value="<?php getValue('email'); ?>"
               placeholder="Email"
            >
            <?php echo $userDataValidator->getError(ErrorMessage::$emailInvalid); ?>
            <?php echo $userDataValidator->getError(ErrorMessage::$emailTaken); ?>

            <input
               required
               type="email"
               name="emailConfirm"
               value="<?php getValue('emailConfirm'); ?>"
               placeholder="Confirm email"
            >
            <?php echo $userDataValidator->getError(ErrorMessage::$emailsDoNotMatch); ?>

            <input
               required
               type="password"
               name="password"
               placeholder="Password"
            >
            <?php echo $userDataValidator->getError(ErrorMessage::$passwordInsecure); ?>
            <?php echo $userDataValidator->getError(ErrorMessage::$passwordLength); ?>

            <input
               required
               type="password"
               name="passwordConfirm"
               placeholder="Confirm password"
            >
            <?php echo $userDataValidator->getError(ErrorMessage::$passwordsDoNotMatch); ?>

            <input type="submit" name="submitRegisterForm" value="SUBMIT">
            
         </form>
      </div>
      <a class="entryMessage" href="login.php">Already have an account? Log in here.</a>
   </div>
</div>

<?php require_once("includes/entryFooter.php"); ?>
