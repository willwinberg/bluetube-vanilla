<?php
require_once("includes/entryHeader.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php"); 
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/dataProcessors/Error.php"); 
require_once("includes/markupRenderers/FormBuilder.php"); 

$dataSanitizer = new FormInputSanitizer;
$validator = new formInputValidator($db);
$account = new AccountHandler($db);

if (isset($_POST["submitRegisterForm"])) {
   $data = $dataSanitizer->sanitize($_POST);

   $validator->validateFirstName($data["firstName"]);
   $validator->validateLastName($data["lastName"]);
   $validator->validateUsername($data["username"]);
   $validator->validateEmails($data["email"], $data["emailConfirm"]);
   $validator->validatePasswords($data["password"], $data["passwordConfirm"]);

   $noErrors = empty($validator->errors);

   if ($noErrors) {
      $account->registerNewUser($data);
      $_SESSION["loggedIn"] = $data["username"];
      header("Location: index.php");
   }
}

$form = new FormBuilder();

echo $form->openEntryFormTag("Sign Up");
echo $validator->error(Error::$firstNameLength);
   echo $form->entryTextInput("First Name", "firstName");

   echo $validator->error(Error::$lastNameLength);
   echo $form->entryTextInput("Last Name", "lastName");

   echo $validator->error(Error::$usernameLength);
   echo $validator->error(Error::$usernameTaken);
   echo $form->entryTextInput("Username", "username");

   echo $validator->error(Error::$emailInvalid);
   echo $validator->error(Error::$emailTaken);
   echo $form->entryTextInput("Email", "email");

   echo $validator->error(Error::$emailsDoNotMatch);
   echo $form->entryTextInput("Confirm Email", "emailConfirm");

   echo $validator->error(Error::$passwordNotSecure);
   echo $validator->error(Error::$passwordLength);
   echo $form->entryTextInput("Password", "password", "password");

   echo $validator->error(Error::$passwordsDoNotMatch);   
   echo $form->entryTextInput("Confirm Password", "passwordConfirm", "password");

   echo $form->submitButton("SUBMIT", "submitRegisterForm");
echo $form->closeEntryFormTag("login.php", "Already have an account? Log in here.");

require_once("includes/entryFooter.php"); ?>
