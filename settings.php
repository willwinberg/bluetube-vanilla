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
$validator = new formInputValidator($db);
$account = new AccountHandler($db);

$data = $dataSanitizer->sanitize($_POST);

if (isset($_POST["detailsUpdate"])) {
   $validator->validateFirstName($data["firstName"]);
   $validator->validateLastName($data["lastName"]);
   $validator->validateEmails($data["email"], $data["emailConfirm"]);
   
   $noErrors = empty($validator->errors);

   if ($noErrors) {
      $account->updateDetails($data, $loggedInUsername);
   }
}

if (isset($_POST["passwordUpdate"])) {
   $validator->validateOldPassword($data["oldPassword"], $loggedInUsername);
   $validator->validatePasswords($data["newPassword"], $data["passwordConfirm"]);

   $noErrors = empty($validator->errors);

   if ($noErrors) {
      $account->updatePassword($data["newPassword"], $loggedInUsername);
   } else {
      echo "Failure!";
   }
}
?>

<div class="settingsContainer column">
   <?php
   $form = new FormBuilder($user->user);

   echo $account->success();
   echo $form->openFormTag("settings.php");
      echo $form->textInput("First Name", "firstName");
      echo $validator->error(Error::$firstNameLength);

      echo $form->textInput("Last Name", "lastName");
      echo $validator->error(Error::$lastNameLength);

      // echo $form->textInput("Username", "username");
      // echo $validator->error(Error::$usernameLength);
      // echo $validator->error(Error::$usernameTaken);

      echo $form->textInput("Email", "email");
      echo $validator->error(Error::$emailInvalid);
      echo $validator->error(Error::$emailTaken);

      echo $form->textInput("Confirm Email", "emailConfirm");
      echo $validator->error(Error::$emailsDoNotMatch);

      echo $form->submitButton("SUBMIT", "detailsUpdate");
   echo $form->closeFormTag();

   echo $form->openFormTag("settings.php");
      echo $form->textInput("Old Password", "oldPassword", "password");
      echo $validator->error(Error::$passwordIncorrect);

      echo $form->textInput("New Password", "newPassword", "password");
      echo $validator->error(Error::$passwordNotSecure);
      echo $validator->error(Error::$passwordLength);

      echo $form->textInput("Confirm Password", "passwordConfirm", "password");
      echo $validator->error(Error::$passwordsDoNotMatch);   

      echo $form->submitButton("SUBMIT", "passwordUpdate");
   echo $form->closeFormTag();
   ?>
</div>

<?php
require_once("includes/footer.php");
?>