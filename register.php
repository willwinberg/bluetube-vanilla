<?php
require_once("includes/entryHeader.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php"); 
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/UserEntryHandler.php");
require_once("includes/dataProcessors/Error.php"); 
require_once("includes/markupRenderers/FormBuilder.php"); 

$dataSanitizer = new FormInputSanitizer;
$dataValidator = new formInputValidator($db);
$entryHandler = new UserEntryHandler($db);

if (isset($_POST["submitRegisterForm"])) {
   $sanitizedData = $dataSanitizer->sanitize($_POST);

   $dataValidator->validateNewUserData($sanitizedData);
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
      <div class="entryForm">
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

            echo $form->passwordInput("Password", "password");
            echo $dataValidator->getError(Error::$passwordNotSecure);
            echo $dataValidator->getError(Error::$passwordLength);

            echo $form->passwordInput("Confirm Password", "passwordConfirm");
            echo $dataValidator->getError(Error::$passwordsDoNotMatch);            

            echo $form->submitButton("SUBMIT", "submitRegisterForm");
         echo $form->closeFormTag();
         ?>       
      </div>
      <a class="entryMessage" href="login.php">Already have an account? Log in here.</a>
   </div>
</div>

<?php require_once("includes/entryFooter.php"); ?>
