<?php 
require_once("includes/header.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/markupRenderers/FormBuilder.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<?php
if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$validator = new formInputValidator($db);
$account = new AccountHandler($db);

$data = FormInputSanitizer::sanitize($_POST);

if (isset($_POST["detailsUpdate"])) {
   $validator->changesMade($user->basicDataArray());
   $validator->validateFirstName($data["firstName"]);
   $validator->validateLastName($data["lastName"]);
   $validator->validateEmails($data["email"], $data["emailConfirm"], $user->email);
   
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
   }
}

if (isset($_POST["imageUpdate"])) {
   $path = $validator->validateImage();
   $noErrors = empty($validator->errors);

   if ($noErrors) {
      $account->updateImage($path, $loggedInUsername);
   }
}
?>
<div class="row">
   <div class="col-7">
      <?php
      $form = new FormBuilder($user->basicDataArray());

      echo $form->openFormTag("Modify Personal Information");
         echo $account->success(Success::$detailsUpdate);
         echo $validator->error(Error::$noChanges);
         echo $validator->error(Error::$firstNameLength);
         echo $form->textInput("First Name", "firstName");

         echo $validator->error(Error::$lastNameLength);
         echo $form->textInput("Last Name", "lastName");

         echo $validator->error(Error::$emailInvalid);
         echo $validator->error(Error::$emailTaken);
         echo $form->textInput("Email", "email");

         echo $validator->error(Error::$emailsDoNotMatch);
         echo $form->textInput("Confirm Email", "emailConfirm");

         echo $form->submitButton("Submit", "detailsUpdate");
      echo $form->closeFormTag();
      ?>
   </div>
   <div class ="col-5">
      <?php
      echo $form->openFormTag("Change your profile picture", "multipart/form-data");
         echo $account->success(Success::$image);
         echo $form->imageInput("image", $user->image());
         foreach ($validator->errors as $error) echo "<li>" . $error . "</li>";
         echo $form->submitButton("Submit", "imageUpdate");
      echo $form->closeFormTag();
      ?>
   </div>
</div>
<div class="row">
   <div class="col-7">
      <?php
      echo $form->openFormTag("Change your password");
         echo $account->success(Success::$passwordUpdate);
         echo $validator->error(Error::$passwordIncorrect);
         echo $form->textInput("Old Password", "oldPassword", "password");

         echo $validator->error(Error::$passwordNotSecure);
         echo $validator->error(Error::$passwordLength);
         echo $form->textInput("New Password", "newPassword", "password");

         echo $validator->error(Error::$passwordsDoNotMatch);   
         echo $form->textInput("Confirm Password", "passwordConfirm", "password");

         echo $form->submitButton("Submit", "passwordUpdate");
      echo $form->closeFormTag();
      ?>
   </div>
</div>

<?php require_once("includes/footer.php"); ?>