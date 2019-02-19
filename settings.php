<?php 
require_once("includes/header.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/markupRenderers/FormBuilder.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$dataSanitizer = new FormInputSanitizer;
$validator = new formInputValidator($db);
$account = new AccountHandler($db);

$data = $dataSanitizer->sanitize($_POST);

$inputChanged = sizeof(array_diff($data, $user->user)) > 1;;

if (isset($_POST["detailsUpdate"])) {
   $validator->validateFirstName($data["firstName"]);
   $validator->validateLastName($data["lastName"]);
   $validator->validateEmails($data["email"], $data["emailConfirm"], $user->email);
   
   $noErrors = empty($validator->errors);

   if ($noErrors && $inputChanged) {
      $account->updateDetails($data, $loggedInUsername);
   }
}

if (isset($_POST["passwordUpdate"])) {
   $validator->validateOldPassword($data["oldPassword"], $loggedInUsername);
   $validator->validatePasswords($data["newPassword"], $data["passwordConfirm"]);

   $noErrors = empty($validator->errors);

   if ($noErrors) {
      $account->updatePassword($data["newPassword"], $loggedInUsername);
   }
}

$form = new FormBuilder($user->user);

echo $form->openFormTag();
   echo $account->success();
   echo $validator->error(Error::$firstNameLength);
   echo $form->textInput("First Name", "firstName");

   echo $validator->error(Error::$lastNameLength);
   echo $form->textInput("Last Name", "lastName");

   echo $validator->error(Error::$emailInvalid);
   echo $validator->error(Error::$emailTaken);
   echo $form->textInput("Email", "email");

   echo $validator->error(Error::$emailsDoNotMatch);
   echo $form->textInput("Confirm Email", "emailConfirm");

   echo $form->submitButton("SUBMIT", "detailsUpdate");
echo $form->closeFormTag();

echo $form->openFormTag("settings.php");
   echo $validator->error(Error::$passwordIncorrect);
   echo $form->textInput("Old Password", "oldPassword", "password");

   echo $validator->error(Error::$passwordNotSecure);
   echo $validator->error(Error::$passwordLength);
   echo $form->textInput("New Password", "newPassword", "password");

   echo $validator->error(Error::$passwordsDoNotMatch);   
   echo $form->textInput("Confirm Password", "passwordConfirm", "password");

   echo $form->submitButton("SUBMIT", "passwordUpdate");
echo $form->closeFormTag();

require_once("includes/footer.php");
?>