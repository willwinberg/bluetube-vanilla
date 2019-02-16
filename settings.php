<?php 
require_once("includes/header.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/dataProcessors/Error.php"); 
require_once("includes/markupRenderers/FormBuilder.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$dataSanitizer = new FormInputSanitizer;
$dataValidator = new formInputValidator($db);
$account = new AccountHandler($db);

$detailsUpdate = isset($_POST["detailsUpdate"]);
$passwordUpdate = isset($_POST["passwordUpdate"]);

if ($detailsUpdate || $passwordUpdate) {
   $sanitizedData = $dataSanitizer->sanitize($_POST);
   var_dump($sanitizedData);
   echo "here";

   $dataValidator->validateUserData($sanitizedData);
   $noErrors = empty($dataValidator->errorArray);

   if ($noErrors) {
      if ($detailsUpdate) $account->updateDetails($sanitizedData, $loggedInUsername);
      if ($passwordUpdate) $account->updatePassword($sanitizedData, $loggedInUsername);
      var_dump($user);
   }
}
?>

<div class="settingsContainer column">
   <?php
   $form = new FormBuilder();

   echo $form->openFormTag("settings.php");
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

      echo $form->submitButton("SUBMIT", "detailsUpdate");
   echo $form->closeFormTag();

   echo $form->openFormTag("settings.php");
      echo $form->textInput("Old Password", "oldPassword", "password");
      echo $dataValidator->getError(Error::$passwordIncorrect);

      echo $form->textInput("New Password", "password", "password");
      echo $dataValidator->getError(Error::$passwordNotSecure);
      echo $dataValidator->getError(Error::$passwordLength);

      echo $form->textInput("Confirm Password", "passwordConfirm", "password");
      echo $dataValidator->getError(Error::$passwordsDoNotMatch);   

      echo $form->submitButton("SUBMIT", "passwordUpdate");
   echo $form->closeFormTag();
   ?>
</div>

<?php
require_once("includes/footer.php");
?>