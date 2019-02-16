<?php
require_once("includes/entryHeader.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php"); 
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/dataProcessors/Error.php"); 
require_once("includes/markupRenderers/FormBuilder.php"); 

$dataSanitizer = new FormInputSanitizer;
$dataValidator = new formInputValidator($db);
$entryHandler = new AccountHandler($db);

if (isset($_POST["submitRegisterForm"])) {
   $sanitizedData = $dataSanitizer->sanitize($_POST);

   $dataValidator->validateUserData($sanitizedData);
   $noErrors = empty($dataValidator->errorArray);

   if ($noErrors) {
      $entryHandler->registerNewUser($sanitizedData);
      $_SESSION["loggedIn"] = $sanitizedData["username"];
      header("Location: index.php");
   }
}
?>

<div class="entryContainer">
   <div class="column">
      <div class="entryHeader">
         <img src="assets/images/logo.png" title="logo" alt="BlueTube Logo"/>
         <h3>Sign Up</h3>
         <span>to continue to BlueTube</span>
      </div>
      <?php
      $form = new FormBuilder($custom = true);

      echo $form->openFormTag("register.php");
         echo $form->textInput("First Name", "firstName");
         echo $dataValidator->getError(Error::$firstNameLength);

         echo $form->textInput("Last Name", "lastName");
         echo $dataValidator->getError(Error::$lastNameLength);

         echo $form->textInput("Username", "username");
         echo $dataValidator->getError(Error::$usernameLength);
         echo $dataValidator->getError(Error::$usernameTaken);

         echo $form->textInput("Email", "email");
         echo $dataValidator->getError(Error::$emailInvalid);
         echo $dataValidator->getError(Error::$emailTaken);


         echo $form->textInput("Confirm Email", "emailConfirm");
         echo $dataValidator->getError(Error::$emailsDoNotMatch);

         echo $form->textInput("Password", "password", "password");
         echo $dataValidator->getError(Error::$passwordNotSecure);
         echo $dataValidator->getError(Error::$passwordLength);

         echo $form->textInput("Confirm Password", "passwordConfirm", "password");
         echo $dataValidator->getError(Error::$passwordsDoNotMatch);   

         echo $form->submitButton("SUBMIT", "submitRegisterForm");
      echo $form->closeFormTag();
      ?>       
      <a class="entryMessage" href="login.php">
         Already have an account? Log in here.
      </a>
   </div>
</div>

<?php require_once("includes/entryFooter.php"); ?>
