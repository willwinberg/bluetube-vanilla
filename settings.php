<?php 
require_once("includes/header.php");
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/FormInputValidator.php");
require_once("includes/dataProcessors/ImageProcessor.php");
require_once("includes/markupRenderers/FormBuilder.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<?php
if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

$validator = new formInputValidator($db);
$user = $loggedInUser;

$data = FormInputSanitizer::sanitize($_POST);

if (isset($_POST["detailsUpdate"])) {
   $validator->changesMade($user->basicDataArray());
   $validator->validateFirstName($data["firstName"]);
   $validator->validateLastName($data["lastName"]);
   $validator->validateEmails(
      $data["email"],
      $data["emailConfirm"],
      $user->email()
   );

   if (empty($validator->errors)) {
      $detailsMessage = $user->updateDetails($data);
   }
}

if (isset($_POST["passwordUpdate"])) {
   $validator->validateOldPassword($data["oldPassword"], $user->username());
   $validator->validatePasswords($data["newPassword"], $data["passwordConfirm"]);

   if (empty($validator->errors)) {
      $passwordMessage = $user->updatePassword($data["newPassword"]);
   }
}

$imageProcessor = new ImageProcessor($db);
$image = $user->image();

if (isset($_POST["imageUpdate"])) {
   $imageProcessor->validateImage();

   if (empty($imageProcessor->errors)) {
      $imageMessage = $imageProcessor->updateImagePath($user->username());
      $image = $imageProcessor->finalPath;
   }
}
?>

<div class="row">
   <div class="col-7">
      <?php
      $form = new FormBuilder($user->basicDataArray());

      echo $form->openFormTag("Modify Personal Information");
         echo $detailsMessage;
         echo $validator->error(Error::$noChanges);
         echo $form->textInput("First Name", "firstName");
         echo $validator->error(Error::$firstNameLength);

         echo $form->textInput("Last Name", "lastName");
         echo $validator->error(Error::$lastNameLength);

         echo $form->textInput("Email", "email");
         echo $validator->error(Error::$emailInvalid);
         echo $validator->error(Error::$emailTaken);

         echo $form->textInput("Confirm Email", "emailConfirm");
         echo $validator->error(Error::$emailsDoNotMatch);

         echo $form->submitButton("Submit", "detailsUpdate");
      echo $form->closeFormTag();
      ?>
   </div>
   <div class ="col-5">
      <?php
      echo $form->openFormTag("Change your profile picture", "multipart/form-data");
         echo $imageMessage;
         echo $form->imageInput("image", $image);
         echo $imageProcessor->errors();
         echo $form->submitButton("Submit", "imageUpdate");
      echo $form->closeFormTag();
      ?>
   </div>
</div>
<div class="row">
   <div class="col-7">
      <?php
      echo $form->openFormTag("Change your password");
      echo $passwordMessage;
      echo $form->textInput("Old Password", "oldPassword", "password");
      echo $validator->error(Error::$passwordIncorrect);

      echo $form->textInput("New Password", "newPassword", "password");
      echo $validator->error(Error::$passwordNotSecure);
      echo $validator->error(Error::$passwordLength);

      echo $form->textInput("Confirm Password", "passwordConfirm", "password");
      echo $validator->error(Error::$passwordsDoNotMatch);   

         echo $form->submitButton("Submit", "passwordUpdate");
      echo $form->closeFormTag();
      ?>
   </div>
</div>

<?php require_once("includes/footer.php"); ?>